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

        $amountInCents = $cart->total() * 100;

        $payment = $user->pay($amountInCents);

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
                    $invoice->file_path,
                    now()->format('d/m/Y'),
                    Order::find($orderId)->orderItems()->sum('price')
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
        $validated = $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'street' => ['required', 'string', 'max:255'],
                'house_number' => ['required', 'string', 'max:6'],
                'city' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:2'],
                'postal_code' => ['required', 'string', 'max:5'],
                'tax_code' => ['required', 'string', 'max:16'],
                'description' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0'],
                'quantity' => ['required', 'numeric', 'min:1'],
                'note' => ['nullable', 'string', 'max:255'],
            ],
            [],
            [
                'first_name' => 'nome',
                'last_name' => 'cognome',
                'street' => 'indirizzo',
                'house_number' => 'numero civico',
                'city' => 'città',
                'province' => 'provincia',
                'postal_code' => 'CAP',
                'tax_code' => 'codice fiscale',
                'description' => 'descrizione',
                'price' => 'prezzo',
                'quantity' => 'quantità',
            ]
        );

        $admin = User::where('role', 'admin')->firstOrFail();
        $adminData = Admin::where('user_id', $admin->id)->firstOrFail();

        $date = Carbon::now();
        $invoiceNumber = $this->getNextInvoiceNumber();
        $price = (float) $validated['price'];
        $quantity = (float) $validated['quantity'];
        $total = $price * $quantity;

        $html = view('invoices.invoice', [
            'user' => (object) [
                'name' => $validated['first_name'],
                'surname' => $validated['last_name'],
            ],
            'customer' => (object) [
                'street' => $validated['street'],
                'house_number' => $validated['house_number'],
                'postal_code' => $validated['postal_code'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'tax_code' => $validated['tax_code'],
            ],
            'admin' => $admin,
            'adminData' => $adminData,
            'orderItems' => [[
                'description' => $validated['description'],
                'price' => $price,
                'quantity' => $quantity,
                'total' => $total,
            ]],
            'total' => $total,
            'invoiceDate' => $date->format('d/m/Y'),
            'invoiceNumber' => $invoiceNumber,
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
            'issued_at' => $date,
            'order_id' => null,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.invoices.created');
    }

    public function preparePayment(Request $request)
    {
        $request->merge([
            'price' => str_replace(',', '.', (string) $request->input('price')),
        ]);

        $validated = $request->validate(
            [
                'description' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0.01'],
                'quantity' => ['required', 'integer', 'min:1'],
            ],
            [],
            [
                'description' => 'descrizione',
                'price' => 'prezzo',
                'quantity' => 'quantità',
            ]
        );

        session()->put('extra_payment_description', $validated['description']);
        session()->put('extra_payment_price', $validated['price']);
        session()->put('extra_payment_quantity', $validated['quantity']);

        if (($validated['price'] * $validated['quantity']) > 77.47) {
            return back()->withError('Importo superiore a 77.47 € (max consentito)');
        }

        return redirect()->route('payment.pay');
    }

    public function processIndividualPayment(Request $request)
    {
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $amountInCents = session()->get('extra_payment_price')
            * session()->get('extra_payment_quantity')
            * 100;

        $payment = $user->pay($amountInCents);

        return response()->json([
            'clientSecret' => $payment->client_secret
        ]);
    }

    private function getNextInvoiceNumber(): int
    {
        $lastInvoice = Invoice::orderByDesc('issued_at')->orderByDesc('number')->first();

        if (!$lastInvoice || !$lastInvoice->issued_at) {
            return 1;
        }

        if (Carbon::parse($lastInvoice->issued_at)->year !== now()->year) {
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
