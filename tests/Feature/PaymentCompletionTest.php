<?php

namespace Tests\Feature;

use App\Enums\PaymentPurpose;
use App\Exceptions\PaymentVerificationException;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Payments\PaymentGateway;
use App\Payments\PaymentIntentData;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_succeeded_payment_is_completed_once(): void
    {
        $gateway = new CompletionPaymentGateway(
            new PaymentIntentData(
                id: 'pi_success',
                clientSecret: 'pi_success_secret',
                status: 'succeeded',
                amount: 1500,
                currency: 'eur',
            )
        );
        $this->app->instance(PaymentGateway::class, $gateway);

        $user = User::factory()->create();
        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'stripe_payment_intent_id' => 'pi_success',
            'purpose' => PaymentPurpose::CHECKOUT,
            'amount' => 1500,
            'currency' => 'eur',
            'status' => 'requires_payment_method',
            'context' => ['items' => []],
        ]);
        $fulfillmentCalls = 0;
        $service = $this->app->make(PaymentService::class);

        $first = $service->complete(
            $user,
            'pi_success',
            function () use (&$fulfillmentCalls) {
                $fulfillmentCalls++;

                return null;
            }
        );
        $second = $service->complete(
            $user,
            'pi_success',
            function () use (&$fulfillmentCalls) {
                $fulfillmentCalls++;

                return null;
            }
        );

        $this->assertSame(1, $fulfillmentCalls);
        $this->assertTrue($first->isCompleted());
        $this->assertTrue($second->isCompleted());
        $this->assertSame('succeeded', $transaction->fresh()->status);
    }

    public function test_payment_with_wrong_amount_is_rejected(): void
    {
        $gateway = new CompletionPaymentGateway(
            new PaymentIntentData(
                id: 'pi_wrong_amount',
                clientSecret: 'pi_wrong_amount_secret',
                status: 'succeeded',
                amount: 1400,
                currency: 'eur',
            )
        );
        $this->app->instance(PaymentGateway::class, $gateway);

        $user = User::factory()->create();
        PaymentTransaction::create([
            'user_id' => $user->id,
            'stripe_payment_intent_id' => 'pi_wrong_amount',
            'purpose' => PaymentPurpose::CHECKOUT,
            'amount' => 1500,
            'currency' => 'eur',
            'status' => 'requires_payment_method',
            'context' => ['items' => []],
        ]);

        $this->expectException(PaymentVerificationException::class);
        $this->expectExceptionMessage('Payment amount does not match.');

        $this->app->make(PaymentService::class)->complete(
            $user,
            'pi_wrong_amount',
            fn () => null
        );
    }

    public function test_payment_owned_by_another_user_is_rejected(): void
    {
        $gateway = new CompletionPaymentGateway(
            new PaymentIntentData(
                id: 'pi_other_user',
                clientSecret: 'pi_other_user_secret',
                status: 'succeeded',
                amount: 1500,
                currency: 'eur',
            )
        );
        $this->app->instance(PaymentGateway::class, $gateway);

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        PaymentTransaction::create([
            'user_id' => $owner->id,
            'stripe_payment_intent_id' => 'pi_other_user',
            'purpose' => PaymentPurpose::CHECKOUT,
            'amount' => 1500,
            'currency' => 'eur',
            'status' => 'requires_payment_method',
            'context' => ['items' => []],
        ]);

        $this->expectException(PaymentVerificationException::class);

        $this->app->make(PaymentService::class)->complete(
            $otherUser,
            'pi_other_user',
            fn () => null
        );
    }
}

class CompletionPaymentGateway implements PaymentGateway
{
    public function __construct(private readonly PaymentIntentData $intent) {}

    public function create(User $user, int $amount, array $metadata): PaymentIntentData
    {
        return $this->intent;
    }

    public function retrieve(User $user, string $paymentIntentId): PaymentIntentData
    {
        return $this->intent;
    }
}
