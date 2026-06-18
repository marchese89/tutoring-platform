<?php

namespace App\Http\Controllers\Student;

use App\Helpers\NumberHelper;
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
                ->withError(__('student.payment.details_missing'));
        }

        return view('student.pay', [
            'formattedTotal' => NumberHelper::format((float) $price * (int) $quantity),
            'stripeKey' => config('services.stripe.key'),
        ]);
    }
}
