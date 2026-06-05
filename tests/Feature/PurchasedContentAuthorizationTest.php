<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchasedContentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_open_an_unpurchased_lesson(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Protected lesson',
            'number' => 1,
            'price' => 20,
        ]);

        $this->actingAs($student->user)
            ->get(route('student.lessons.show', [$course, $lesson]))
            ->assertForbidden();

        $this->assertDatabaseMissing('chats', [
            'product_id' => $lesson->id,
            'product_type' => CartItem::LESSON,
            'student_id' => $student->id,
        ]);
    }

    public function test_student_can_open_a_purchased_lesson(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Purchased lesson',
            'number' => 1,
            'price' => 20,
        ]);
        $this->purchase($student, $lesson->id, CartItem::LESSON);

        $this->actingAs($student->user)
            ->get(route('student.lessons.show', [$course, $lesson]))
            ->assertOk();
    }

    public function test_student_cannot_open_an_unpurchased_exercise(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $exercise = Exercise::create([
            'course_id' => $course->id,
            'title' => 'Protected exercise',
            'price' => 10,
        ]);

        $this->actingAs($student->user)
            ->get(route('student.exercises.show', [$course, $exercise]))
            ->assertForbidden();
    }

    public function test_student_can_open_a_free_exercise(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $exercise = Exercise::create([
            'course_id' => $course->id,
            'title' => 'Free exercise',
            'price' => 0,
        ]);

        $this->actingAs($student->user)
            ->get(route('student.exercises.show', [$course, $exercise]))
            ->assertOk();
    }

    public function test_student_cannot_purchase_another_students_direct_request(): void
    {
        $owner = $this->createStudent();
        $otherStudent = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'student_id' => $owner->id,
            'title' => 'Private request',
            'price' => 25,
        ]);

        $this->actingAs($otherStudent->user)
            ->post(route('cart.items.store', [
                'id' => $lessonRequest->id,
                'type' => CartItem::REQUESTED_LESSON,
            ]))
            ->assertForbidden();
    }

    public function test_student_cannot_view_another_students_direct_request(): void
    {
        $owner = $this->createStudent();
        $otherStudent = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'student_id' => $owner->id,
            'title' => 'Private request',
            'price' => 25,
        ]);

        $this->actingAs($otherStudent->user)
            ->get(route('student.direct-requests.show', $lessonRequest))
            ->assertForbidden();
    }

    public function test_paid_direct_request_cannot_be_purchased_again(): void
    {
        $student = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'student_id' => $student->id,
            'title' => 'Paid request',
            'price' => 25,
            'is_paid' => true,
        ]);

        $this->actingAs($student->user)
            ->post(route('cart.items.store', [
                'id' => $lessonRequest->id,
                'type' => CartItem::REQUESTED_LESSON,
            ]))
            ->assertForbidden();
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

    private function purchase(Student $student, int $productId, int $productType): void
    {
        $order = Order::create([
            'student_id' => $student->id,
            'ordered_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $productId,
            'product_type' => $productType,
            'price' => 20,
            'description' => 'Test purchase',
        ]);
    }
}
