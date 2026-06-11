<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function pay(Request $request): RedirectResponse|View
    {
        $price = $request->session()->get('extra_payment_price');
        $quantity = $request->session()->get('extra_payment_quantity');

        if (! is_numeric($price) || ! is_numeric($quantity)) {
            return redirect()
                ->route('payment.extra')
                ->withError('Inserisci i dettagli del pagamento prima di procedere.');
        }

        return view('student.pay', [
            'formattedTotal' => number_format((float) $price * (int) $quantity, 2, ',', '.'),
            'stripeKey' => config('services.stripe.key'),
        ]);
    }
}
