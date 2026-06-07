<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBillingOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_order_page_uses_prepared_order_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->createStudent();
        $order = Order::create([
            'student_id' => $student->id,
            'ordered_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 10,
            'product_type' => CartItem::LESSON,
            'price' => 25,
            'description' => 'Lesson purchase',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order->id))
            ->assertOk()
            ->assertSee('Ordine #' . $order->id)
            ->assertSee('Lezione')
            ->assertSee('25,00', false);
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
}
