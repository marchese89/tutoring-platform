<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Invoice;
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

    public function test_only_owner_can_read_direct_student_invoice(): void
    {
        Storage::fake('private');
        $owner = $this->createStudent();
        $otherStudent = $this->createStudent();
        $path = 'extra-invoices/2026/invoice_303.pdf';
        Storage::disk('private')->put($path, 'extra invoice');

        Invoice::create([
            'number' => 303,
            'issued_at' => now(),
            'student_id' => $owner->id,
            'file_path' => $path,
        ]);

        $this->actingAs($owner->user)
            ->get(route('protected-files.show', ['path' => $path]))
            ->assertOk()
            ->assertStreamedContent('extra invoice');

        $this->actingAs($otherStudent->user)
            ->get(route('protected-files.show', ['path' => $path]))
            ->assertNotFound();
    }

    public function test_student_can_preview_lesson_request_file_stored_in_session(): void
    {
        Storage::fake('private');
        $student = $this->createStudent();
        $path = 'lesson_requests/request_files/draft.pdf';
        Storage::disk('private')->put($path, 'draft request');

        $this->actingAs($student->user)
            ->withSession(['uploaded_lesson_request_file' => $path])
            ->get(route('protected-files.show', ['path' => $path]))
            ->assertOk()
            ->assertStreamedContent('draft request');
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
