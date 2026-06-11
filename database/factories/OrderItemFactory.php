<?php

namespace Database\Factories;

use App\Http\Utility\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => fake()->unique()->numberBetween(1, 100000),
            'product_type' => CartItem::LESSON,
            'price' => fake()->numberBetween(5, 100),
            'description' => fake()->sentence(),
        ];
    }
}
