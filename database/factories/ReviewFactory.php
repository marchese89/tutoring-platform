<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->paragraph(),
        ];
    }
}
