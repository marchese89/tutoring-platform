<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => UserRole::STUDENT->value]),
            'street' => fake()->streetName(),
            'house_number' => (string) fake()->buildingNumber(),
            'city' => fake()->city(),
            'province' => strtoupper(fake()->lexify('??')),
            'postal_code' => fake()->numerify('#####'),
            'tax_code' => strtoupper(fake()->unique()->bothify('????????????????')),
        ];
    }
}
