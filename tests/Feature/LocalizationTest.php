<?php

namespace Tests\Feature;

use App\Models\Student;
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
            ->assertSee('Update credentials')
            ->assertSee('Password requirements');

        $response = $this->actingAs($student->user)
            ->withSession(['locale' => 'en'])
            ->post(route('student.account.address.update'), []);

        $response->assertSessionHasErrors([
            'postal_code' => 'The postal code field is required.',
        ]);
    }
}
