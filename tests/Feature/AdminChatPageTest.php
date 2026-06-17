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
            ->withSession(['locale' => 'en'])
            ->get(route('admin.chats.show', $chat->id))
            ->assertOk()
            ->assertSee('Chat with student')
            ->assertSee('Requested lesson no.')
            ->assertSee('Student request')
            ->assertSee('Solution')
            ->assertSee('Conversation')
            ->assertSee('Mario Rossi')
            ->assertSee('Requested lesson')
            ->assertSee('Prepared chat message')
            ->assertSee('src="/protected-files/lesson_requests/request.pdf#view=FitH"', false)
            ->assertSee('src="/protected-files/lesson_requests/solution.pdf#view=FitH"', false)
            ->assertSee('window.Echo', false);
    }

    public function test_admin_chat_list_follows_the_session_locale(): void
    {
        /** @var User $admin */
        $admin = User::factory()->createOne(['role' => 'admin']);
        $student = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'title' => 'Requested lesson list item',
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
            'message' => 'Unread message',
            'sender_role' => ChatSenderRole::STUDENT->value,
            'sent_at' => now(),
        ]);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.chats.index'))
            ->assertOk()
            ->assertSee('Student chats')
            ->assertSee('Chat list')
            ->assertSee('Requested lesson')
            ->assertSee('Unread')
            ->assertSee('View chat');
    }

    public function test_admin_chat_list_is_paginated(): void
    {
        /** @var User $admin */
        $admin = User::factory()->createOne(['role' => 'admin']);
        $student = $this->createStudent();

        for ($index = 0; $index < 11; $index++) {
            $lessonRequest = LessonRequest::create([
                'title' => $index === 0 ? 'Newest paginated chat' : 'Paginated chat '.$index,
                'student_id' => $student->id,
                'request_file' => 'lesson_requests/request-'.$index.'.pdf',
                'solution_file' => 'lesson_requests/solution-'.$index.'.pdf',
                'is_fulfilled' => true,
                'is_paid' => true,
                'requested_at' => now()->subMinutes($index),
            ]);

            Chat::create([
                'product_id' => $lessonRequest->id,
                'product_type' => ProductType::REQUESTED_LESSON->value,
                'student_id' => $student->id,
                'created_at' => now()->subMinutes($index),
            ]);
        }

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.chats.index'))
            ->assertOk()
            ->assertSee('Newest paginated chat')
            ->assertDontSee('Paginated chat 10')
            ->assertSee('page=2', false);
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
