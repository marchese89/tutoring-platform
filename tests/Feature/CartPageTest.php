<?php

namespace Tests\Feature;

use App\Http\Utility\Cart;
use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_uses_prepared_cart_summary(): void
    {
        $student = $this->createStudent();
        $lesson = $this->createLesson('Cart lesson', 30);
        $cart = new Cart;
        $cart->add(new CartItem($lesson->id, CartItem::LESSON));

        $this->actingAs($student->user)
            ->withSession(['cart' => $cart])
            ->get(route('cart.show'))
            ->assertOk()
            ->assertSee('Cart lesson')
            ->assertSee('30');
    }

    public function test_checkout_page_uses_prepared_cart_total(): void
    {
        config(['services.stripe.key' => 'pk_test_cart']);
        $student = $this->createStudent();
        $lesson = $this->createLesson('Checkout lesson', 45);
        $cart = new Cart;
        $cart->add(new CartItem($lesson->id, CartItem::LESSON));

        $this->actingAs($student->user)
            ->withSession(['cart' => $cart])
            ->get(route('checkout.show'))
            ->assertOk()
            ->assertSee('45,00&euro;', false)
            ->assertSee('pk_test_cart')
            ->assertSee('data-intent-url="' . route('payment.process') . '"', false)
            ->assertSee('data-return-url="' . route('payment.success') . '"', false)
            ->assertSee('https://js.stripe.com/v3/');
    }

    public function test_navbar_uses_prepared_cart_item_count(): void
    {
        $student = $this->createStudent();
        $cart = new Cart;
        $cart->add(new CartItem($this->createLesson('First lesson', 10)->id, CartItem::LESSON));
        $cart->add(new CartItem($this->createLesson('Second lesson', 20)->id, CartItem::LESSON));

        $this->actingAs($student->user)
            ->withSession(['cart' => $cart])
            ->get(route('cart.show'))
            ->assertOk()
            ->assertSee('Carrello: 2 articoli');
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

    private function createLesson(string $title, int $price): Lesson
    {
        $themeArea = ThemeArea::create(['name' => fake()->unique()->word()]);
        $subject = Subject::create([
            'theme_area_id' => $themeArea->id,
            'name' => fake()->unique()->word(),
        ]);
        $course = Course::create([
            'subject_id' => $subject->id,
            'name' => fake()->unique()->words(2, true),
        ]);

        return Lesson::create([
            'course_id' => $course->id,
            'title' => $title,
            'number' => 1,
            'price' => $price,
        ]);
    }
}
