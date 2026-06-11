<?php

namespace Database\Factories;

use App\Http\Utility\CartItem;
use App\Models\Chat;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chat>
 */
class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'product_id' => Lesson::factory(),
            'product_type' => CartItem::LESSON,
            'student_id' => Student::factory(),
        ];
    }
}
