<?php

namespace Tests\Feature;

use App\Models\LessonRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLessonRequestListTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_request_list_uses_prepared_display_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'title' => 'Prepared request',
            'student_id' => $student->id,
            'request_file' => 'lesson_requests/request.pdf',
            'requested_at' => '2026-06-10 14:30:00',
            'is_fulfilled' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.lesson-requests.index'))
            ->assertOk()
            ->assertSee('Prepared request')
            ->assertSee('10-06-2026 14:30')
            ->assertSee(route('admin.lesson-requests.show', $lessonRequest->id));
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
}
