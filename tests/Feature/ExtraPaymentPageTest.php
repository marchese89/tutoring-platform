<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtraPaymentPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_page_uses_prepared_session_data(): void
    {
        config(['services.stripe.key' => 'pk_test_example']);
        $student = $this->createStudent();

        $this->actingAs($student->user)
            ->withSession([
                'extra_payment_price' => 12.50,
                'extra_payment_quantity' => 2,
            ])
            ->get(route('payment.pay'))
            ->assertOk()
            ->assertSee('25,00&euro;', false)
            ->assertSee('pk_test_example');
    }

    public function test_payment_page_redirects_when_details_are_missing(): void
    {
        $student = $this->createStudent();

        $this->actingAs($student->user)
            ->get(route('payment.pay'))
            ->assertRedirect(route('payment.extra'))
            ->assertSessionHas('error');
    }

    public function test_rejected_total_is_not_stored_in_session(): void
    {
        $student = $this->createStudent();

        $this->actingAs($student->user)
            ->from(route('payment.extra'))
            ->post(route('checkout.payment.prepare'), [
                'description' => 'Expensive service',
                'price' => '50',
                'quantity' => '2',
            ])
            ->assertRedirect(route('payment.extra'))
            ->assertSessionHas('error')
            ->assertSessionMissing('extra_payment_price')
            ->assertSessionMissing('extra_payment_quantity');
    }

    private function createStudent(): Student
    {
        $user = User::factory()->create(['role' => 'student']);

        return Student::create([
            'user_id' => $user->id,
            'street' => 'Test street',
            'house_number' => '1',
            'city' => 'Rome',
            'province' => 'RM',
            'postal_code' => '00100',
            'tax_code' => strtoupper(fake()->unique()->bothify('????????????????')),
        ]);
    }
}
