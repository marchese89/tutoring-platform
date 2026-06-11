<?php

namespace Tests\Feature;

use App\Http\Utility\CartItem;
use App\Models\Invoice;
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
            ->assertSee('Ordine #'.$order->id)
            ->assertSee('Lezione')
            ->assertSee('25,00', false);
    }

    public function test_admin_invoice_page_uses_controller_order_id(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->createStudent();
        $order = Order::create([
            'student_id' => $student->id,
            'ordered_at' => now(),
        ]);

        Invoice::create([
            'number' => 1,
            'issued_at' => now(),
            'order_id' => $order->id,
            'file_path' => 'invoices/order.pdf',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.invoice', $order->id))
            ->assertOk()
            ->assertSee('Fattura Ordine #'.$order->id)
            ->assertSee('invoices/order.pdf');
    }

    public function test_admin_invoice_page_returns_not_found_without_invoice(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.orders.invoice', 999))
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
}
