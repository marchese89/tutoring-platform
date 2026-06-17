<?php

namespace Database\Factories;

use App\Enums\PaymentPurpose;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => UserRole::STUDENT->value]),
            'order_id' => null,
            'stripe_payment_intent_id' => 'pi_'.fake()->unique()->uuid(),
            'purpose' => PaymentPurpose::CHECKOUT,
            'amount' => fake()->numberBetween(500, 10000),
            'currency' => 'eur',
            'status' => PaymentStatus::REQUIRES_PAYMENT_METHOD->value,
            'context' => [],
            'completed_at' => null,
            'receipt_sent_at' => null,
        ];
    }
}
