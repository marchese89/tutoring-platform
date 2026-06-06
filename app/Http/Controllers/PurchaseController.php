<?php

namespace App\Http\Controllers;

use App\Enums\PaymentPurpose;
use App\Exceptions\PaymentVerificationException;
use App\Http\Utility\Cart;
use App\Http\Utility\CartItem;
use App\Mail\OrderCompletedMail;
use App\Models\Admin;
use App\Models\Exercise;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\InvoiceNumberService;
use App\Services\InvoiceService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function addToCart(Request $request, int $id, int $type)
    {
        if ($type === CartItem::REQUESTED_LESSON) {
            $this->authorize('purchase', LessonRequest::findOrFail($id));
        }

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

    public function processPayment(Request $request, PaymentService $payments)
    {
        $user = $request->user();
        $cart = $this->cart($request);

        if ($cart->count() === 0) {
            return response()->json(['error' => 'Cart empty'], 400);
        }

        $items = array_map(fn (CartItem $item) => [
            'id' => $item->id(),
            'type' => $item->type(),
            'price' => $item->price(),
            'name' => $item->name(),
        ], $cart->items());

        $payment = $payments->createIntent(
            $user,
            PaymentPurpose::CHECKOUT,
            $cart->total() * 100,
            ['items' => $items]
        );

        return response()->json([
            'clientSecret' => $payment->clientSecret,
        ]);
    }

    public function completePurchase(
        Request $request,
        OrderService $orderService,
        InvoiceService $invoiceService,
        PaymentService $payments
    ) {
        try {
            $user = $request->user();
            $paymentIntentId = $request->string('payment_intent')->toString();

            if ($paymentIntentId === '') {
                throw new PaymentVerificationException('Missing payment intent.');
            }

            $transaction = $payments->complete(
                $user,
                $paymentIntentId,
                function (PaymentTransaction $transaction) use ($user, $orderService) {
                    if ($transaction->purpose === PaymentPurpose::EXTRA) {
                        return null;
                    }

                    $items = $transaction->context['items'] ?? [];

                    if ($items === []) {
                        throw new PaymentVerificationException('Checkout items are missing.');
                    }

                    return $orderService->processSnapshot($user->student, $items);
                }
            );
        } catch (PaymentVerificationException $exception) {
            return redirect()
                ->route('checkout.show')
                ->withErrors(['payment' => $exception->getMessage()]);
        }

        if ($transaction->purpose === PaymentPurpose::EXTRA) {
            $invoiceService->generateExtraPaymentPdf($user, $transaction);

            $request->session()->forget([
                'extra_payment_description',
                'extra_payment_price',
                'extra_payment_quantity',
            ]);

            return redirect()->route('payment.ok');
        }

        $invoice = $invoiceService->generatePdf($transaction->order_id, $transaction->id);

        if (! $transaction->receipt_sent_at) {
            Mail::to($user->email)->send(
                new OrderCompletedMail(
                    $user,
                    $invoice->file_path,
                    now()->format('d/m/Y'),
                    Order::findOrFail($transaction->order_id)->orderItems()->sum('price')
                )
            );

            $transaction->update(['receipt_sent_at' => now()]);
        }

        $cart = $this->cart($request);
        $cart->clear();
        $request->session()->put('cart', $cart);

        return redirect()->route('payment.complete');
    }

    public function createExtraInvoice(
        Request $request,
        InvoiceNumberService $numbers
    ) {
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
        $invoiceNumber = $numbers->next($date->year);
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

        $dompdf = new Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $path = "extra-invoices/{$date->year}/invoice_{$invoiceNumber}.pdf";
        Storage::disk('private')->put($path, $dompdf->output());

        Invoice::create([
            'number' => $invoiceNumber,
            'issued_at' => $date,
            'order_id' => null,
            'source' => 'extra',
            'total_amount' => (int) round($total * 100),
            'currency' => 'eur',
            'customer_snapshot' => [
                'name' => $validated['first_name'],
                'surname' => $validated['last_name'],
                'street' => $validated['street'],
                'house_number' => $validated['house_number'],
                'postal_code' => $validated['postal_code'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'tax_code' => $validated['tax_code'],
            ],
            'line_items' => [[
                'description' => $validated['description'],
                'unit_price' => (int) round($price * 100),
                'quantity' => $quantity,
                'total' => (int) round($total * 100),
            ]],
            'note' => $validated['note'] ?? null,
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

    public function createExtraPaymentIntent(Request $request, PaymentService $payments)
    {
        $user = $request->user();
        $description = $request->session()->get('extra_payment_description');
        $price = $request->session()->get('extra_payment_price');
        $quantity = $request->session()->get('extra_payment_quantity');

        if (! $description || ! is_numeric($price) || ! is_numeric($quantity)) {
            return response()->json(['error' => 'Extra payment details are missing.'], 422);
        }

        $payment = $payments->createIntent(
            $user,
            PaymentPurpose::EXTRA,
            (int) round(((float) $price) * ((int) $quantity) * 100),
            [
                'description' => $description,
                'unit_price' => (float) $price,
                'quantity' => (int) $quantity,
            ]
        );

        return response()->json([
            'clientSecret' => $payment->clientSecret,
        ]);
    }

    private function cart(Request $request): Cart
    {
        $cart = $request->session()->get('cart');

        if (! $cart instanceof Cart) {
            $cart = new Cart;
            $request->session()->put('cart', $cart);
        }

        return $cart;
    }
}
