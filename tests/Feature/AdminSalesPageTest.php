<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSalesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_page_uses_prepared_month_labels(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->createStudent();

        Order::create([
            'student_id' => $student->id,
            'ordered_at' => '2026-06-07 10:00:00',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.sales.index'))
            ->assertOk()
            ->assertSee('Giugno');
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
