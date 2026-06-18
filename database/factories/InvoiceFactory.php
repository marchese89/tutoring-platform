<?php

namespace Database\Factories;

use App\Enums\InvoiceSource;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(500, 20000);

        return [
            'number' => fake()->unique()->numberBetween(1, 999999),
            'issued_at' => fake()->dateTimeBetween('-1 year'),
            'order_id' => null,
            'student_id' => Student::factory(),
            'payment_transaction_id' => null,
            'source' => InvoiceSource::EXTRA->value,
            'total_amount' => $amount,
            'currency' => 'eur',
            'customer_snapshot' => [
                'name' => fake()->firstName(),
                'surname' => fake()->lastName(),
            ],
            'line_items' => [[
                'description' => fake()->sentence(),
                'unit_price' => $amount,
                'quantity' => 1,
                'total' => $amount,
            ]],
            'note' => null,
            'file_path' => 'extra-invoices/'.now()->year.'/'.fake()->uuid().'.pdf',
        ];
    }
}
