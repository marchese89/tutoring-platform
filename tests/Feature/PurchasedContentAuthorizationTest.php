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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchasedContentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_open_an_unpurchased_lesson(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create([
            'name' => 'Purchased course',
        ]);
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
        $student = Student::factory()->create();
        $course = Course::factory()->create([
            'name' => 'Purchased course',
        ]);
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Purchased lesson',
            'number' => 1,
            'price' => 20,
            'presentation_file' => 'lessons/presentations/lesson.pdf',
            'content_file' => 'lessons/files/lesson.pdf',
        ]);
        $this->purchase($student, $lesson->id, CartItem::LESSON);

        $this->actingAs($student->user)
            ->get(route('student.lessons.show', [$course, $lesson]))
            ->assertOk()
            ->assertSee('title="Presentazione"', false)
            ->assertSee('title="Contenuto"', false)
            ->assertSee('src="/protected-files/lessons/presentations/lesson.pdf#view=FitH"', false)
            ->assertSee('src="/protected-files/lessons/files/lesson.pdf#view=FitH"', false);
    }

    public function test_student_cannot_open_an_unpurchased_exercise(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create([
            'name' => 'Purchased course',
        ]);
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
        $student = Student::factory()->create();
        $course = Course::factory()->create([
            'name' => 'Purchased course',
        ]);
        $exercise = Exercise::create([
            'course_id' => $course->id,
            'title' => 'Free exercise',
            'price' => 0,
            'prompt_file' => 'exercises/trace/exercise.pdf',
            'solution_file' => 'exercises/execution/exercise.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.exercises.show', [$course, $exercise]))
            ->assertOk()
            ->assertSee('title="Traccia"', false)
            ->assertSee('title="Esecuzione"', false)
            ->assertSee('src="/protected-files/exercises/trace/exercise.pdf#view=FitH"', false)
            ->assertSee('src="/protected-files/exercises/execution/exercise.pdf#view=FitH"', false);
    }

    public function test_student_cannot_purchase_another_students_direct_request(): void
    {
        $owner = Student::factory()->create();
        $otherStudent = Student::factory()->create();
        $lessonRequest = LessonRequest::factory()->create([
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
        $owner = Student::factory()->create();
        $otherStudent = Student::factory()->create();
        $lessonRequest = LessonRequest::factory()->create([
            'student_id' => $owner->id,
            'title' => 'Private request',
            'price' => 25,
        ]);

        $this->actingAs($otherStudent->user)
            ->get(route('student.direct-requests.show', $lessonRequest))
            ->assertForbidden();
    }

    public function test_student_can_view_owned_paid_direct_request_documents(): void
    {
        $student = Student::factory()->create();
        $lessonRequest = LessonRequest::factory()->create([
            'student_id' => $student->id,
            'title' => 'Owned paid request',
            'price' => 25,
            'is_paid' => true,
            'request_file' => 'lesson_requests/request_files/request.pdf',
            'solution_file' => 'lesson_requests/solutions/solution.pdf',
        ]);

        $this->actingAs($student->user)
            ->get(route('student.direct-requests.show', $lessonRequest))
            ->assertOk()
            ->assertSee('title="Richiesta dello studente"', false)
            ->assertSee('title="Soluzione"', false)
            ->assertSee('src="/protected-files/lesson_requests/request_files/request.pdf#view=FitH"', false)
            ->assertSee('src="/protected-files/lesson_requests/solutions/solution.pdf#view=FitH"', false);
    }

    public function test_paid_direct_request_cannot_be_purchased_again(): void
    {
        $student = Student::factory()->create();
        $lessonRequest = LessonRequest::factory()->create([
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

    public function test_student_cannot_add_missing_catalog_content_to_cart(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student->user)
            ->post(route('cart.items.store', [
                'id' => 999999,
                'type' => CartItem::LESSON,
            ]))
            ->assertNotFound();

        $this->actingAs($student->user)
            ->post(route('cart.items.store', [
                'id' => 999999,
                'type' => CartItem::EXERCISE,
            ]))
            ->assertNotFound();
    }

    private function purchase(Student $student, int $productId, int $productType): void
    {
        $order = Order::factory()
            ->for($student)
            ->create();

        OrderItem::factory()
            ->for($order)
            ->create([
                'product_id' => $productId,
                'product_type' => $productType,
                'price' => 20,
                'description' => 'Test purchase',
            ]);
    }
}
