<?php

namespace Database\Factories;

use App\Models\InvoiceSequence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceSequence>
 */
class InvoiceSequenceFactory extends Factory
{
    protected $model = InvoiceSequence::class;

    public function definition(): array
    {
        return [
            'year' => fake()->unique()->numberBetween(2020, 2100),
            'last_number' => fake()->numberBetween(0, 500),
        ];
    }
}
