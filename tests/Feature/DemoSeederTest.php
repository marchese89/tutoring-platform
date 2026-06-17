<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Chat;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\Review;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeders_create_a_complete_demo_installation(): void
    {
        Storage::fake('private');

        $this->seed();

        $this->assertSame(3, User::count());
        $this->assertSame(1, Admin::count());
        $this->assertSame(2, Student::count());
        $this->assertSame(2, ThemeArea::count());
        $this->assertSame(2, Subject::count());
        $this->assertSame(2, Course::count());
        $this->assertSame(3, Lesson::count());
        $this->assertSame(2, Exercise::count());
        $this->assertSame(2, LessonRequest::count());
        $this->assertSame(12, Order::count());
        $this->assertSame(12, Invoice::count());
        $this->assertSame(1, Chat::count());
        $this->assertSame(1, Review::count());

        $this->assertDatabaseHas('users', [
            'email' => env('SEED_ADMIN_EMAIL', 'admin@example.com'),
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => env('SEED_STUDENT_EMAIL', 'student@example.com'),
            'role' => 'student',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => env('SEED_STUDENT_2_EMAIL', 'student2@example.com'),
            'role' => 'student',
        ]);

        Storage::disk('private')->assertExists('demo/lessons/routes-presentation.pdf');
        Storage::disk('private')->assertExists('demo/requests/laravel-refactoring-solution.pdf');
    }
}
