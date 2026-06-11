<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPurchasedCourseListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_course_page_lists_only_purchased_items(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create([
            'name' => 'Purchased course',
        ]);
        $purchasedLesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Purchased lesson',
            'number' => 1,
            'price' => 20,
        ]);
        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Unpurchased lesson',
            'number' => 2,
            'price' => 20,
        ]);
        $purchasedExercise = Exercise::create([
            'course_id' => $course->id,
            'title' => 'Purchased exercise',
            'price' => 15,
        ]);
        Exercise::create([
            'course_id' => $course->id,
            'title' => 'Unpurchased exercise',
            'price' => 15,
        ]);

        $this->purchase($student, $purchasedLesson->id, CartItem::LESSON, 20);
        $this->purchase($student, $purchasedExercise->id, CartItem::EXERCISE, 15);

        $this->actingAs($student->user)
            ->get(route('student.courses.show', $course->id))
            ->assertOk()
            ->assertSee('Purchased lesson')
            ->assertSee('Purchased exercise')
            ->assertDontSee('Unpurchased lesson')
            ->assertDontSee('Unpurchased exercise');
    }

    public function test_student_courses_index_lists_courses_with_purchased_items(): void
    {
        $student = Student::factory()->create();
        $purchasedCourse = Course::factory()->create([
            'name' => 'Purchased course',
        ]);
        $unrelatedCourse = Course::factory()->create([
            'name' => 'Unrelated course',
        ]);
        $lesson = Lesson::create([
            'course_id' => $purchasedCourse->id,
            'title' => 'Purchased lesson',
            'number' => 1,
            'price' => 20,
        ]);
        Lesson::create([
            'course_id' => $unrelatedCourse->id,
            'title' => 'Other lesson',
            'number' => 1,
            'price' => 20,
        ]);

        $this->purchase($student, $lesson->id, CartItem::LESSON, 20);

        $this->actingAs($student->user)
            ->get(route('student.courses.index'))
            ->assertOk()
            ->assertSee('Purchased course')
            ->assertDontSee('Unrelated course');
    }

    private function purchase(Student $student, int $productId, int $productType, int $price): void
    {
        $order = Order::factory()
            ->for($student)
            ->create();

        OrderItem::factory()
            ->for($order)
            ->create([
                'product_id' => $productId,
                'product_type' => $productType,
                'price' => $price,
                'description' => 'Test purchase',
            ]);
    }
}
