<?php

namespace Tests\Feature;

use App\Enums\ChatSenderRole;
use App\Enums\ProductType;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ChatAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([MessageSent::class]);
    }

    public function test_student_can_send_messages_to_owned_chat(): void
    {
        $student = $this->createStudent();
        $chat = $this->createChat($student);

        $response = $this->actingAs($student->user)->postJson(
            route('student.chat.messages.store'),
            [
                'chat_id' => $chat->id,
                'message' => 'Owned chat message',
            ]
        );

        $response->assertOk();
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'message' => 'Owned chat message',
            'sender_role' => ChatSenderRole::STUDENT->value,
        ]);
    }

    public function test_student_cannot_send_messages_to_another_students_chat(): void
    {
        $owner = $this->createStudent();
        $otherStudent = $this->createStudent();
        $chat = $this->createChat($owner);

        $response = $this->actingAs($otherStudent->user)->postJson(
            route('student.chat.messages.store'),
            [
                'chat_id' => $chat->id,
                'message' => 'Unauthorized message',
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseMissing('chat_messages', [
            'chat_id' => $chat->id,
            'message' => 'Unauthorized message',
        ]);
    }

    public function test_admin_can_send_messages_to_any_chat(): void
    {
        $student = $this->createStudent();
        $chat = $this->createChat($student);
        /** @var User $admin */
        $admin = User::factory()->createOne(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(
            route('admin.chat.messages.store'),
            [
                'chat_id' => $chat->id,
                'message' => 'Admin message',
            ]
        );

        $response->assertOk();
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'message' => 'Admin message',
            'sender_role' => ChatSenderRole::ADMIN->value,
        ]);
    }

    public function test_messages_are_broadcast_on_private_chat_channels(): void
    {
        $student = $this->createStudent();
        $chat = $this->createChat($student);
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'Private message',
            'sender_role' => ChatSenderRole::STUDENT->value,
            'sent_at' => now(),
        ]);

        $channels = (new MessageSent($message))->broadcastOn();

        $this->assertCount(1, $channels);
        $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
        $this->assertSame("private-chat.{$chat->id}", $channels[0]->name);
    }

    public function test_broadcast_authentication_route_is_registered(): void
    {
        $route = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($route) => $route->uri() === 'broadcasting/auth');

        $this->assertNotNull($route);
        $this->assertContains('POST', $route->methods());
    }

    public function test_support_chat_component_prepares_messages_for_each_role(): void
    {
        $student = $this->createStudent();
        $chat = $this->createChat($student);
        $messages = collect([
            ChatMessage::create([
                'chat_id' => $chat->id,
                'message' => 'Student message',
                'sender_role' => ChatSenderRole::STUDENT->value,
                'sent_at' => now(),
            ]),
            ChatMessage::create([
                'chat_id' => $chat->id,
                'message' => 'Admin message',
                'sender_role' => ChatSenderRole::ADMIN->value,
                'sent_at' => now(),
            ]),
        ]);

        $studentHtml = Blade::render(
            '<x-ui.support-chat :chat="$chat" :messages="$messages" />',
            compact('chat', 'messages')
        );
        $adminHtml = Blade::render(
            '<x-ui.support-chat :chat="$chat" :messages="$messages" :own-author="\App\Enums\ChatSenderRole::ADMIN->value" own-sender="Tu" other-sender="Studente" />',
            compact('chat', 'messages')
        );

        $this->assertStringContainsString('Student message', $studentHtml);
        $this->assertStringContainsString('Insegnante', $studentHtml);
        $this->assertStringContainsString('Admin message', $adminHtml);
        $this->assertStringContainsString('Studente', $adminHtml);
    }

    private function createStudent(): Student
    {
        $user = User::factory()->create(['role' => 'student']);

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

    private function createChat(Student $student): Chat
    {
        return Chat::create([
            'product_id' => fake()->unique()->numberBetween(1, 100000),
            'product_type' => ProductType::REQUESTED_LESSON->value,
            'student_id' => $student->id,
        ]);
    }
}
