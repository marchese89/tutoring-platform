<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPurchasedCourseListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_course_page_lists_only_purchased_items(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
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
        $student = $this->createStudent();
        $purchasedCourse = $this->createCourse('Purchased course');
        $unrelatedCourse = $this->createCourse('Unrelated course');
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

    private function createCourse(?string $name = null): Course
    {
        $themeArea = ThemeArea::create(['name' => fake()->unique()->word()]);
        $subject = Subject::create([
            'theme_area_id' => $themeArea->id,
            'name' => fake()->unique()->word(),
        ]);

        return Course::create([
            'subject_id' => $subject->id,
            'name' => $name ?? fake()->unique()->words(2, true),
        ]);
    }

    private function purchase(Student $student, int $productId, int $productType, int $price): void
    {
        $order = Order::create([
            'student_id' => $student->id,
            'ordered_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $productId,
            'product_type' => $productType,
            'price' => $price,
            'description' => 'Test purchase',
        ]);
    }
}
