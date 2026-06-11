<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition(): array
    {
        return [
            'chat_id' => Chat::factory(),
            'message' => fake()->paragraph(),
            'sender_role' => fake()->numberBetween(0, 1),
            'sent_at' => fake()->dateTimeBetween('-1 month'),
        ];
    }
}
