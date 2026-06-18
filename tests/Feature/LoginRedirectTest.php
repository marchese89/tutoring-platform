<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_prepares_lesson_request_return_flag(): void
    {
        $this->get(route('login', ['back' => 1]))
            ->assertOk()
            ->assertSee('name="return" value="1"', false);
    }

    public function test_student_can_return_to_lesson_request_after_login(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'return' => '1',
        ])->assertRedirect(route('lesson-requests.create'));
    }

    public function test_student_without_return_flag_opens_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'return' => '0',
        ])->assertRedirect(route('student.dashboard'));
    }
}
