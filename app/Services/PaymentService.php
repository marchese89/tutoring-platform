<?php

namespace App\Services;

use App\Enums\PaymentPurpose;
use App\Enums\PaymentStatus;
use App\Exceptions\PaymentVerificationException;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Payments\PaymentGateway;
use App\Payments\PaymentIntentData;
use Closure;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;
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
            'status' => PaymentStatus::CREATING->value,
            'context' => $context,
        ]);

        try {
            $intent = $this->gateway->create($user, $amount, [
                'payment_transaction_id' => (string) $transaction->id,
                'purpose' => $purpose->value,
            ]);
        } catch (Throwable $exception) {
            $transaction->update(['status' => PaymentStatus::FAILED->value]);

            throw $exception;
        }

        $transaction->update([
            'stripe_payment_intent_id' => $intent->id,
            'currency' => strtolower($intent->currency),
            'status' => $intent->status,
        ]);

        return $intent;
    }

    public function complete(
        User $user,
        string $paymentIntentId,
        Closure $fulfill
    ): PaymentTransaction {
        $transaction = PaymentTransaction::query()
            ->where('user_id', $user->id)
            ->where('stripe_payment_intent_id', $paymentIntentId)
            ->first();

        if (! $transaction) {
            throw new PaymentVerificationException('Payment transaction not found.');
        }

        if ($transaction->isCompleted()) {
            return $transaction;
        }

        $intent = $this->gateway->retrieve($user, $paymentIntentId);

        $this->verifyIntent($transaction, $intent);

        return DB::transaction(function () use ($transaction, $intent, $fulfill) {
            $lockedTransaction = PaymentTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->id);

            if ($lockedTransaction->isCompleted()) {
                return $lockedTransaction;
            }

            $orderId = $fulfill($lockedTransaction);

            if ($orderId !== null && ! is_int($orderId)) {
                throw new RuntimeException('Payment fulfillment must return an order ID or null.');
            }

            $lockedTransaction->update([
                'order_id' => $orderId,
                'status' => $intent->status,
                'completed_at' => now(),
            ]);

            return $lockedTransaction->fresh();
        });
    }

    private function verifyIntent(
        PaymentTransaction $transaction,
        PaymentIntentData $intent
    ): void {
        if (! $intent->succeeded()) {
            throw new PaymentVerificationException('Payment has not succeeded.');
        }

        if ($intent->amount !== $transaction->amount) {
            throw new PaymentVerificationException('Payment amount does not match.');
        }

        if (strtolower($intent->currency) !== strtolower($transaction->currency)) {
            throw new PaymentVerificationException('Payment currency does not match.');
        }
    }
}
