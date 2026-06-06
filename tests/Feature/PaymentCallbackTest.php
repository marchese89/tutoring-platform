<?php

namespace Tests\Feature;

use App\Enums\PaymentPurpose;
use App\Models\Admin;
use App\Models\Invoice;
use App\Models\PaymentTransaction;
use App\Models\Student;
use App\Models\User;
use App\Payments\PaymentGateway;
use App\Payments\PaymentIntentData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_success_visit_does_not_create_an_order(): void
    {
        $user = $this->studentUser();

        $response = $this->actingAs($user)->get(route('payment.success'));

        $response->assertRedirect(route('checkout.show'));
        $response->assertSessionHasErrors('payment');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_extra_payment_completion_does_not_fulfill_the_cart(): void
    {
        $user = $this->studentUser();
        $this->createAdmin();
        Storage::fake('private');

        $this->app->instance(
            PaymentGateway::class,
            new CallbackPaymentGateway(
                new PaymentIntentData(
                    id: 'pi_extra',
                    clientSecret: 'pi_extra_secret',
                    status: 'succeeded',
                    amount: 2500,
                    currency: 'eur',
                )
            )
        );

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'stripe_payment_intent_id' => 'pi_extra',
            'purpose' => PaymentPurpose::EXTRA,
            'amount' => 2500,
            'currency' => 'eur',
            'status' => 'requires_payment_method',
            'context' => [
                'description' => 'Private lesson',
                'unit_price' => 25,
                'quantity' => 1,
            ],
        ]);

        $response = $this->actingAs($user)->get(route('payment.success', [
            'payment_intent' => 'pi_extra',
        ]));

        $response->assertRedirect(route('payment.ok'));
        $this->assertDatabaseCount('orders', 0);
        $this->assertTrue($transaction->fresh()->isCompleted());

        $invoice = Invoice::firstOrFail();

        $this->assertSame($user->student->id, $invoice->student_id);
        $this->assertSame($transaction->id, $invoice->payment_transaction_id);
        Storage::disk('private')->assertExists($invoice->file_path);

        $this->actingAs($user)->get(route('payment.success', [
            'payment_intent' => 'pi_extra',
        ]));

        $this->assertDatabaseCount('invoices', 1);
    }

    private function studentUser(): User
    {
        $user = User::factory()->create([
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'street' => 'Via Roma',
            'house_number' => '1',
            'city' => 'Roma',
            'province' => 'RM',
            'postal_code' => '00100',
            'tax_code' => 'RSSMRA80A01H501U',
        ]);

        return $user;
    }

    private function createAdmin(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $user->id,
            'street' => 'Via Admin',
            'house_number' => '1',
            'city' => 'Roma',
            'province' => 'RM',
            'postal_code' => '00100',
            'tax_code' => 'ADMADM80A01H501U',
            'vat_number' => 'IT12345678901',
        ]);
    }
}

class CallbackPaymentGateway implements PaymentGateway
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
