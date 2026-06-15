<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_student_use_shared_account_forms(): void
    {
        $admin = Admin::factory()->create();
        $student = Student::factory()->create();

        $this->actingAs($admin->user)
            ->get(route('admin.account.credentials'))
            ->assertOk()
            ->assertSee('data-credentials-settings', false)
            ->assertSee($admin->user->email);

        $this->actingAs($admin->user)
            ->get(route('admin.account.address'))
            ->assertOk()
            ->assertSee('data-address-form', false)
            ->assertSee($admin->street);

        $this->actingAs($student->user)
            ->get(route('student.account.credentials'))
            ->assertOk()
            ->assertSee('data-credentials-settings', false)
            ->assertSee($student->user->email);

        $this->actingAs($student->user)
            ->get(route('student.account.profile'))
            ->assertOk()
            ->assertSee('data-address-form', false)
            ->assertSee($student->street);
    }

    public function test_admin_and_student_password_updates_share_strong_validation(): void
    {
        $admin = Admin::factory()->create();
        $student = Student::factory()->create();

        $this->actingAs($admin->user)
            ->post(route('admin.account.password.update'), [
                'current_password' => 'password',
                'password' => 'weak',
                'password_confirmation' => 'weak',
            ])
            ->assertSessionHasErrors('password');

        $this->actingAs($student->user)
            ->post(route('student.account.password.update'), [
                'current_password' => 'password',
                'password' => 'weak',
                'password_confirmation' => 'weak',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_student_can_update_password_with_shared_credentials_controller(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student->user)
            ->post(route('student.account.password.update'), [
                'current_password' => 'password',
                'password' => 'ValidPassword1!',
                'password_confirmation' => 'ValidPassword1!',
            ])
            ->assertRedirect(route('student.account.credentials'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('ValidPassword1!', $student->user->fresh()->password));
    }
}
