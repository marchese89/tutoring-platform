<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Utility\Cart;
use App\Http\Utility\CartItem;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;
use App\Services\InvoiceService;
use App\Mail\OrderCompletedMail;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class PurchaseController extends Controller
{
    public function addToCart(Request $request, int $id, int $type)
    {
        $cart = $this->cart($request);

        $item = new CartItem($id, $type);
        $cart->add($item);

        $request->session()->put('cart', $cart);

        return match ($type) {
            CartItem::LESSON => redirect()->route('courses.show', Lesson::find($id)->course_id),
            CartItem::EXERCISE => redirect()->route('courses.show', Exercise::find($id)->course_id),
            CartItem::COURSE_LESSONS,
            CartItem::FULL_COURSE => redirect()->route('courses.show', $id),
            CartItem::REQUESTED_LESSON => redirect()->route('student.direct-requests.show', $id),
            default => redirect()->route('cart.show')
        };
    }

    public function removeFromCart(Request $request, int $id, int $type)
    {
        $cart = $this->cart($request);

        if ($cart->count() === 0) {
            return response()->json(['error' => 'Cart empty'], 400);
        }

        $cart->remove($id, $type);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.show');
    }

    public function processPayment(Request $request)
    {
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $cart = $this->cart($request);

        if ($cart->count() === 0) {
            return response()->json(['error' => 'Cart empty'], 400);
        }

        $tot = $cart->total() * 100;

        $payment = $user->pay($tot);

        return response()->json([
            'clientSecret' => $payment->client_secret
        ]);
    }

    public function completePurchase(
        Request $request,
        OrderService $orderService,
        InvoiceService $invoiceService
    ) {
        DB::beginTransaction();

        try {
            $user = $request->user();

            $student = Student::where('user_id', $user->id)->first();

            $cart = $this->cart($request);

            if ($cart->count() === 0) {
                throw new \Exception('Cart empty');
            }

            /*
             * 1. Create order and rows
             */
            $orderId = $orderService->process($student, $cart);

            /*
             * 2. Generate PDF
             */
            $invoiceService->generatePdf($orderId);

            $invoice = Invoice::where('order_id', $orderId)->first();

            /*
             * 3. Send email
             */
            Mail::to($user->email)->send(
                new OrderCompletedMail(
                    $user,
                    $invoice->path,
                    now()->format('d/m/Y'),
                    Order::find($orderId)->order_products()->sum('price')
                )
            );

            /*
             * 4. Clear cart
             */
            $cart->clear();
            $request->session()->put('cart', $cart);

            DB::commit();

            return redirect()->route('payment.complete');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createExtraInvoice(Request $request)
    {
        $validated = $request->validate([
            'inputNome' => ['required', 'string', 'max:255'],
            'inputCognome' => ['required', 'string', 'max:255'],
            'inputIndirizzo' => ['required', 'string', 'max:255'],
            'inputNumeroCivico' => ['required', 'string', 'max:6'],
            'inputCitta' => ['required', 'string', 'max:255'],
            'inputProvincia' => ['required', 'string', 'max:2'],
            'inputCAP' => ['required', 'string', 'max:5'],
            'inputCF' => ['required', 'string', 'max:16'],
            'descrizione' => ['required', 'string', 'max:255'],
            'prezzo' => ['required', 'numeric', 'min:0'],
            'qta' => ['required', 'numeric', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $admin = User::where('role', 'admin')->firstOrFail();
        $adminData = Admin::where('user_id', $admin->id)->firstOrFail();

        $date = Carbon::now();
        $invoiceNumber = $this->getNextInvoiceNumber();
        $price = (float) $validated['prezzo'];
        $quantity = (float) $validated['qta'];
        $total = $price * $quantity;

        $html = view('invoices.invoice', [
            'user' => (object) [
                'name' => $validated['inputNome'],
                'surname' => $validated['inputCognome'],
            ],
            'studente' => (object) [
                'street' => $validated['inputIndirizzo'],
                'house_number' => $validated['inputNumeroCivico'],
                'postal_code' => $validated['inputCAP'],
                'city' => $validated['inputCitta'],
                'province' => $validated['inputProvincia'],
                'cf' => $validated['inputCF'],
            ],
            'admin' => $admin,
            'adminData' => $adminData,
            'order_products' => [[
                'description' => $validated['descrizione'],
                'price' => $price,
                'quantity' => $quantity,
                'total' => $total,
            ]],
            'total' => $total,
            'dataFattura' => $date->format('d/m/Y'),
            'numeroFattura' => $invoiceNumber,
            'note' => $validated['note'] ?? '',
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $path = "extra-invoices/invoice_{$invoiceNumber}.pdf";
        Storage::disk('private')->put($path, $dompdf->output());

        Invoice::create([
            'number' => $invoiceNumber,
            'date' => $date,
            'order_id' => null,
            'path' => $path,
        ]);

        return redirect()->route('admin.invoices.created');
    }

    public function preparePayment(Request $request)
    {
        $request->merge([
            'prezzo' => str_replace(',', '.', (string) $request->input('prezzo')),
        ]);

        $validated = $request->validate([
            'descrizione' => ['required', 'string', 'max:255'],
            'prezzo' => ['required', 'numeric', 'min:0.01'],
            'qta' => ['required', 'integer', 'min:1'],
        ]);

        $prezzo = $validated['prezzo'];
        $qta = $validated['qta'];

        session()->put('descrizione', $validated['descrizione']);
        session()->put('prezzo', $prezzo);
        session()->put('qta', $qta);

        if (($prezzo * $qta) > 77.47) {
            return back()->withError('Importo superiore a 77.47 € (max consentito)');
        }

        return redirect()->route('payment.pay');
    }

    public function processIndividualPayment(Request $request)
    {
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $tot = session()->get('prezzo') * session()->get('qta') * 100;

        $payment = $user->pay($tot);

        return response()->json([
            'clientSecret' => $payment->client_secret
        ]);
    }

    private function getNextInvoiceNumber(): int
    {
        $lastInvoice = Invoice::orderByDesc('date')->orderByDesc('number')->first();

        if (!$lastInvoice || !$lastInvoice->date) {
            return 1;
        }

        if (Carbon::parse($lastInvoice->date)->year !== now()->year) {
            return 1;
        }

        return ((int) $lastInvoice->number) + 1;
    }

    private function cart(Request $request): Cart
    {
        $cart = $request->session()->get('cart');

        if (!$cart instanceof Cart) {
            $cart = new Cart();
            $request->session()->put('cart', $cart);
        }

        return $cart;
    }
}
