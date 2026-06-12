<?php

namespace Database\Factories;

use App\Enums\ChatSenderRole;
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
            'sender_role' => fake()->randomElement([
                ChatSenderRole::STUDENT->value,
                ChatSenderRole::ADMIN->value,
            ]),
            'sent_at' => fake()->dateTimeBetween('-1 month'),
        ];
    }
}
