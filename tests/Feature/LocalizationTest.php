<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_italian_is_the_default_locale(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<html lang="it">', false)
            ->assertSee('Accedi al tuo account');
    }

    public function test_user_can_switch_to_a_supported_locale(): void
    {
        $this->from(route('login'))
            ->post(route('locale.update'), ['locale' => 'en'])
            ->assertRedirect(route('login'))
            ->assertSessionHas('locale', 'en');

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('Access your account')
            ->assertDontSee('Accedi al tuo account');

        $this->get(route('theme-areas.index'))
            ->assertOk()
            ->assertSee('Topic areas')
            ->assertSee('No topic areas available');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Private computer science lessons')
            ->assertSee('How I can help')
            ->assertSee('About me');

        $this->get(route('privacy-policy'))
            ->assertOk()
            ->assertSee('Last updated')
            ->assertSee('Information collected');

        $this->get(route('cookie-policy'))
            ->assertOk()
            ->assertSee('Cookie usage')
            ->assertSee('Technical cookies');

        $student = Student::factory()->create();

        Order::factory()
            ->for($student)
            ->create(['ordered_at' => '2026-06-15 10:00:00']);

        $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Student dashboard')
            ->assertSee('Purchased courses')
            ->assertSee('Extra payment');

        $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->get(route('student.orders.index'))
            ->assertOk()
            ->assertSee('Order history')
            ->assertSee('June')
            ->assertSee('Loading orders...');

        $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->get(route('payment.extra'))
            ->assertOk()
            ->assertSee('Payment details')
            ->assertSee('Example: private lesson');

        $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->get(route('student.review'))
            ->assertOk()
            ->assertSee('Rating')
            ->assertSee('Review saved.');

        $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->post(route('checkout.payment.prepare'), [])
            ->assertSessionHasErrors([
                'description' => 'The description field is required.',
            ]);

        $this->actingAs($student->user)
            ->get(route('cart.show'))
            ->assertOk()
            ->assertSee('Your cart is empty');
    }

    public function test_unsupported_locale_is_rejected(): void
    {
        $this->from(route('login'))
            ->post(route('locale.update'), ['locale' => 'de'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('locale')
            ->assertSessionMissing('locale');
    }

    public function test_account_content_and_validation_follow_the_session_locale(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->get(route('student.account.credentials'))
            ->assertOk()
            ->assertSee('Account settings')
            ->assertSee('Update credentials')
            ->assertSee('Password requirements');

        $response = $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->post(route('student.account.address.update'), []);

        $response->assertSessionHasErrors([
            'postal_code' => 'The postal code field is required.',
        ]);
    }

    public function test_admin_billing_content_and_validation_follow_the_session_locale(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.sales.index'))
            ->assertOk()
            ->assertSee('Sales')
            ->assertSee('No orders found');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.invoices.index'))
            ->assertOk()
            ->assertSee('Invoice list')
            ->assertSee('No invoices found');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.invoices.extra'))
            ->assertOk()
            ->assertSee('Create extra invoice')
            ->assertSee('Tax code');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->post(route('admin.invoices.extra.store'), [])
            ->assertSessionHasErrors([
                'first_name' => 'The first name field is required.',
            ]);
    }
}
