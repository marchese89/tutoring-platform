<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'ordered_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
