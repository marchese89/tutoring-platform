<?php

namespace Tests\Feature;

use App\Enums\PaymentPurpose;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Payments\PaymentGateway;
use App\Payments\PaymentIntentData;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentIntentPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_intent_is_persisted_with_server_owned_context(): void
    {
        $gateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $gateway);

        $user = User::factory()->create();
        $service = $this->app->make(PaymentService::class);

        $intent = $service->createIntent(
            $user,
            PaymentPurpose::CHECKOUT,
            1500,
            ['items' => [['id' => 10, 'type' => 0, 'price' => 15]]]
        );

        $transaction = PaymentTransaction::firstOrFail();

        $this->assertSame('pi_test_123', $intent->id);
        $this->assertSame($user->id, $transaction->user_id);
        $this->assertSame(PaymentPurpose::CHECKOUT, $transaction->purpose);
        $this->assertSame(1500, $transaction->amount);
        $this->assertSame('requires_payment_method', $transaction->status);
        $this->assertSame(10, $transaction->context['items'][0]['id']);
        $this->assertSame((string) $transaction->id, $gateway->metadata['payment_transaction_id']);
    }
}

class FakePaymentGateway implements PaymentGateway
{
    public array $metadata = [];

    public function create(User $user, int $amount, array $metadata): PaymentIntentData
    {
        $this->metadata = $metadata;

        return new PaymentIntentData(
            id: 'pi_test_123',
            clientSecret: 'pi_test_123_secret',
            status: 'requires_payment_method',
            amount: $amount,
            currency: 'eur',
        );
    }

    public function retrieve(User $user, string $paymentIntentId): PaymentIntentData
    {
        return new PaymentIntentData(
            id: $paymentIntentId,
            clientSecret: $paymentIntentId.'_secret',
            status: 'succeeded',
            amount: 1500,
            currency: 'eur',
        );
    }
}
