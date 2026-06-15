<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderHistoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_student_order_pages_use_shared_period_table(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create();
        $this->createOrder($student);

        $this->actingAs($admin)
            ->get(route('admin.sales.index'))
            ->assertOk()
            ->assertSee('data-period-orders-table', false)
            ->assertSee(route('admin.orders.table'), false)
            ->assertDontSee('aggiorna_tabella');

        $this->actingAs($student->user)
            ->get(route('student.orders.index'))
            ->assertOk()
            ->assertSee('data-period-orders-table', false)
            ->assertSee(route('student.orders.table'), false)
            ->assertDontSee('aggiorna_tabella');
    }

    public function test_student_order_table_only_returns_owned_orders(): void
    {
        $student = Student::factory()->create();
        $otherStudent = Student::factory()->create();
        $ownedOrder = $this->createOrder($student, 25);
        $this->createOrder($otherStudent, 40);

        $this->actingAs($student->user)
            ->getJson(route('student.orders.table', [
                'year' => 2026,
                'month' => 6,
            ]))
            ->assertOk()
            ->assertJsonCount(1, 'orders')
            ->assertJsonPath('orders.0.id', $ownedOrder->id)
            ->assertJsonPath('total', 25);
    }

    private function createOrder(Student $student, int $price = 20): Order
    {
        $order = Order::factory()
            ->for($student)
            ->create(['ordered_at' => '2026-06-15 10:00:00']);

        OrderItem::factory()
            ->for($order)
            ->create(['price' => $price]);

        return $order;
    }
}
