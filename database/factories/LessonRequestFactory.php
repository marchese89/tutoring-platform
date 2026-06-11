<?php

namespace Database\Factories;

use App\Models\LessonRequest;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonRequest>
 */
class LessonRequestFactory extends Factory
{
    protected $model = LessonRequest::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'title' => fake()->sentence(4),
            'request_file' => 'lesson_requests/request_files/'.fake()->uuid().'.pdf',
            'price' => fake()->numberBetween(10, 100),
            'is_fulfilled' => false,
            'is_paid' => false,
            'requested_at' => fake()->dateTimeBetween('-6 months'),
        ];
    }
}
