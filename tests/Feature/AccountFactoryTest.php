<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Exercise;
use App\Models\Lesson;
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

    public function test_lesson_factory_creates_lesson(): void
    {
        $lesson = Lesson::factory()->create();

        $this->assertDatabaseHas('lessons', ['id' => $lesson->id]);
        $this->assertNotNull($lesson->course);
        $this->assertNotNull($lesson->course->subject);
        $this->assertNotNull($lesson->course->subject->themeArea);
    }

    public function test_exercise_factory_creates_exercise(): void
    {
        $exercise = Exercise::factory()->create();

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id]);
        $this->assertNotNull($exercise->course);
        $this->assertNotNull($exercise->course->subject);
        $this->assertNotNull($exercise->course->subject->themeArea);
    }
}
