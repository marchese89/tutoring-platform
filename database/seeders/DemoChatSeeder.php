<?php

namespace Database\Seeders;

use App\Enums\ChatSenderRole;
use App\Http\Utility\CartItem;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoChatSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', env('SEED_STUDENT_EMAIL', 'student@example.com'))
            ->with('student')
            ->firstOrFail()
            ->student;
        $paidLesson = Lesson::where('title', 'Blade components')->firstOrFail();

        $chat = Chat::create([
            'product_id' => $paidLesson->id,
            'product_type' => CartItem::LESSON,
            'student_id' => $student->id,
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'I have a question about this lesson.',
            'sender_role' => ChatSenderRole::STUDENT->value,
            'sent_at' => now()->subHours(2),
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'Certo, dimmi pure.',
            'sender_role' => ChatSenderRole::ADMIN->value,
            'sent_at' => now()->subHour(),
        ]);
    }
}
