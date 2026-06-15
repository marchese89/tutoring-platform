<?php

namespace Tests\Feature;

use App\Models\LessonRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonRequestPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_form_only_displays_upload_controls_to_students(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create();

        $this->get(route('lesson-requests.create'))
            ->assertOk()
            ->assertSee('Accesso studente richiesto')
            ->assertDontSee('data-upload-progress-form', false);

        $this->actingAs($admin)
            ->get(route('lesson-requests.create'))
            ->assertOk()
            ->assertSee('Accesso studente richiesto')
            ->assertDontSee('data-upload-progress-form', false);

        $this->actingAs($student->user)
            ->get(route('lesson-requests.create'))
            ->assertOk()
            ->assertSee('data-upload-progress-form', false)
            ->assertSee('data-form-file', false);
    }

    public function test_request_lists_use_shared_table_for_admin_and_student(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create();

        LessonRequest::factory()->for($student)->create([
            'title' => 'Richiesta non acquistata',
            'is_paid' => false,
        ]);

        LessonRequest::factory()->for($student)->create([
            'title' => 'Richiesta acquistata',
            'is_paid' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.lesson-requests.index'))
            ->assertOk()
            ->assertSee('data-lesson-request-table', false);

        $this->actingAs($student->user)
            ->get(route('student.direct-requests.index'))
            ->assertOk()
            ->assertSee('data-lesson-request-table', false)
            ->assertSee('Richiesta non acquistata');

        $this->actingAs($student->user)
            ->get(route('student.direct-requests.purchased'))
            ->assertOk()
            ->assertSee('data-lesson-request-table', false)
            ->assertSee('Richiesta acquistata');
    }
}
