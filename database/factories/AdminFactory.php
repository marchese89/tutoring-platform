<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'admin']),
            'tax_code' => strtoupper(fake()->unique()->bothify('????????????????')),
            'street' => fake()->streetName(),
            'house_number' => (string) fake()->buildingNumber(),
            'city' => fake()->city(),
            'province' => strtoupper(fake()->lexify('??')),
            'postal_code' => fake()->numerify('#####'),
            'vat_number' => fake()->numerify('###########'),
        ];
    }
}
