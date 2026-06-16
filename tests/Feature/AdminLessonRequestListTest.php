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

    public function test_student_request_dashboard_follows_the_session_locale(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.students.index'))
            ->assertOk()
            ->assertSee('Student requests')
            ->assertSee('View and manage all requests submitted by students.')
            ->assertSee('Student chats');
    }

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
            ->withSession(['locale' => 'en'])
            ->get(route('admin.lesson-requests.index'))
            ->assertOk()
            ->assertSee('Student requests')
            ->assertSee('Completed')
            ->assertSee('Prepared request')
            ->assertSee('10-06-2026 14:30')
            ->assertSee(route('admin.lesson-requests.show', $lessonRequest->id));
    }

    public function test_lesson_request_detail_uses_shared_document_and_form_components(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = $this->createStudent();
        $lessonRequest = LessonRequest::create([
            'title' => 'Request detail',
            'student_id' => $student->id,
            'request_file' => 'lesson_requests/request_files/request.pdf',
            'solution_file' => 'lesson_requests/solution_files/solution.pdf',
            'price' => 25,
            'is_fulfilled' => false,
        ]);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.lesson-requests.show', $lessonRequest->id))
            ->assertOk()
            ->assertSee('View lesson request')
            ->assertSee('Prompt')
            ->assertSee('Solution')
            ->assertSee('Upload solution')
            ->assertSee('Set price')
            ->assertSee('Request detail')
            ->assertSee('src="/protected-files/lesson_requests/request_files/request.pdf#view=FitH"', false)
            ->assertSee('src="/protected-files/lesson_requests/solution_files/solution.pdf#view=FitH"', false)
            ->assertSee(route('admin.lesson-requests.solution.store', $lessonRequest->id), false)
            ->assertSee(route('admin.lesson-requests.solution.destroy', $lessonRequest->id), false)
            ->assertSee(route('admin.lesson-requests.price.store', $lessonRequest->id), false);
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
