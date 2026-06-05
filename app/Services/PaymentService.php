<?php

namespace App\Services;

use App\Enums\PaymentPurpose;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Payments\PaymentGateway;
use App\Payments\PaymentIntentData;
use InvalidArgumentException;
use Throwable;

class PaymentService
{
    public function __construct(private readonly PaymentGateway $gateway) {}

    public function createIntent(
        User $user,
        PaymentPurpose $purpose,
        int $amount,
        array $context
    ): PaymentIntentData {
        if ($amount < 1) {
            throw new InvalidArgumentException('Payment amount must be positive.');
        }

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'purpose' => $purpose,
            'amount' => $amount,
            'currency' => 'eur',
            'status' => 'creating',
            'context' => $context,
        ]);

        try {
            $intent = $this->gateway->create($user, $amount, [
                'payment_transaction_id' => (string) $transaction->id,
                'purpose' => $purpose->value,
            ]);
        } catch (Throwable $exception) {
            $transaction->update(['status' => 'failed']);

            throw $exception;
        }

        $transaction->update([
            'stripe_payment_intent_id' => $intent->id,
            'currency' => strtolower($intent->currency),
            'status' => $intent->status,
        ]);

        return $intent;
    }
}
