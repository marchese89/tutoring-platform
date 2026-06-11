<?php

namespace Database\Factories;

use App\Models\ThemeArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ThemeArea>
 */
class ThemeAreaFactory extends Factory
{
    protected $model = ThemeArea::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
