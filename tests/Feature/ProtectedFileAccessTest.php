<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProtectedFileAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_read_public_lesson_presentation_from_private_disk(): void
    {
        Storage::fake('private');
        $course = $this->createCourse();
        $path = 'lessons/presentations/public.pdf';
        Storage::disk('private')->put($path, 'presentation');
        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Public presentation',
            'number' => 1,
            'presentation_file' => $path,
            'price' => 20,
        ]);

        $this->get(route('protected-files.show', ['path' => $path]))
            ->assertOk()
            ->assertStreamedContent('presentation');
    }

    public function test_guest_cannot_read_paid_lesson_content(): void
    {
        Storage::fake('private');
        $course = $this->createCourse();
        $path = 'lessons/files/paid.pdf';
        Storage::disk('private')->put($path, 'paid content');
        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Paid lesson',
            'number' => 1,
            'content_file' => $path,
            'price' => 20,
        ]);

        $this->get(route('protected-files.show', ['path' => $path]))
            ->assertNotFound();
    }

    public function test_only_purchasing_student_can_read_paid_lesson_content(): void
    {
        Storage::fake('private');
        $owner = $this->createStudent();
        $otherStudent = $this->createStudent();
        $course = $this->createCourse();
        $path = 'lessons/files/purchased.pdf';
        Storage::disk('private')->put($path, 'purchased content');
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Purchased lesson',
            'number' => 1,
            'content_file' => $path,
            'price' => 20,
        ]);
        $this->purchase($owner, $lesson);

        $this->actingAs($owner->user)
            ->get(route('protected-files.show', ['path' => $path]))
            ->assertOk()
            ->assertStreamedContent('purchased content');

        $this->actingAs($otherStudent->user)
            ->get(route('protected-files.show', ['path' => $path]))
            ->assertNotFound();
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

    private function createCourse(): Course
    {
        $themeArea = ThemeArea::create(['name' => fake()->unique()->word()]);
        $subject = Subject::create([
            'theme_area_id' => $themeArea->id,
            'name' => fake()->unique()->word(),
        ]);

        return Course::create([
            'subject_id' => $subject->id,
            'name' => fake()->unique()->words(2, true),
        ]);
    }

    private function purchase(Student $student, Lesson $lesson): void
    {
        $order = Order::create([
            'student_id' => $student->id,
            'ordered_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $lesson->id,
            'product_type' => CartItem::LESSON,
            'price' => $lesson->price,
            'description' => 'Lesson purchase',
        ]);
    }
}
