<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_factory_creates_student_user(): void
    {
        $student = Student::factory()->create();

        $this->assertSame('student', $student->user->role);
        $this->assertDatabaseHas('students', ['user_id' => $student->user->id]);
    }

    public function test_admin_factory_creates_admin_user(): void
    {
        $admin = Admin::factory()->create();

        $this->assertSame('admin', $admin->user->role);
        $this->assertDatabaseHas('admins', ['user_id' => $admin->user->id]);
    }
}
