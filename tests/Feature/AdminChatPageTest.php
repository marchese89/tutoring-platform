<?php

namespace Tests\Feature;

use App\Enums\ChatSenderRole;
use App\Enums\ProductType;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\LessonRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminChatPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_chat_page_uses_prepared_student_and_message_data(): void
    {
        /** @var User $admin */
        $admin = User::factory()->createOne(['role' => 'admin']);
        $student = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'title' => 'Requested lesson',
            'student_id' => $student->id,
            'request_file' => 'lesson_requests/request.pdf',
            'solution_file' => 'lesson_requests/solution.pdf',
            'is_fulfilled' => true,
            'is_paid' => true,
        ]);
        $chat = Chat::create([
            'product_id' => $lessonRequest->id,
            'product_type' => ProductType::REQUESTED_LESSON->value,
            'student_id' => $student->id,
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'Prepared chat message',
            'sender_role' => ChatSenderRole::STUDENT->value,
            'sent_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.chats.show', $chat->id))
            ->assertOk()
            ->assertSee('Mario Rossi')
            ->assertSee('Requested lesson')
            ->assertSee('Prepared chat message')
            ->assertSee('window.Echo', false);
    }

    private function createStudent(): Student
    {
        $user = User::factory()->create([
            'role' => 'student',
            'name' => 'Mario',
            'surname' => 'Rossi',
        ]);

        return Student::create([
            'user_id' => $user->id,
            'street' => 'Test street',
            'house_number' => '1',
            'city' => 'Rome',
            'province' => 'RM',
            'postal_code' => '00100',
            'tax_code' => strtoupper(fake()->unique()->bothify('????????????????')),
        ]);
    }
}
