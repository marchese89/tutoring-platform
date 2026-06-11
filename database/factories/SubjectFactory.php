<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\ThemeArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'theme_area_id' => ThemeArea::factory(),
            'name' => fake()->unique()->word(),
        ];
    }
}
