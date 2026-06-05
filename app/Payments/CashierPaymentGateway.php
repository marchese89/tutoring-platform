<?php

namespace App\Payments;

use App\Models\User;
use RuntimeException;

class CashierPaymentGateway implements PaymentGateway
{
    public function create(User $user, int $amount, array $metadata): PaymentIntentData
    {
        $user->createOrGetStripeCustomer();

        $payment = $user->pay($amount, [
            'metadata' => $metadata,
        ]);

        return new PaymentIntentData(
            id: $payment->id,
            clientSecret: $payment->client_secret,
            status: $payment->status,
            amount: $payment->amount,
            currency: $payment->currency,
        );
    }

    public function retrieve(User $user, string $paymentIntentId): PaymentIntentData
    {
        $payment = $user->findPayment($paymentIntentId);

        if (! $payment) {
            throw new RuntimeException('Stripe payment intent not found.');
        }

        return new PaymentIntentData(
            id: $payment->id,
            clientSecret: $payment->client_secret,
            status: $payment->status,
            amount: $payment->amount,
            currency: $payment->currency,
        );
    }
}
