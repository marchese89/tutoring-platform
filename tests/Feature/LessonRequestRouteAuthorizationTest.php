<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LessonRequestRouteAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_request_page_but_cannot_use_request_endpoints(): void
    {
        $this->get(route('lesson-requests.create'))->assertOk();

        $this->post(route('lesson-requests.files.store'))
            ->assertRedirect(route('login'));
        $this->delete(route('lesson-requests.files.destroy'))
            ->assertRedirect(route('login'));
        $this->post(route('lesson-requests.store'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_cannot_use_student_request_endpoints(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('lesson-requests.files.store'))
            ->assertRedirect(route('login'));
    }

    public function test_student_can_upload_a_request_file(): void
    {
        Storage::fake('private');
        $student = $this->createStudent();

        $response = $this->actingAs($student->user)
            ->post(route('lesson-requests.files.store'), [
                'file' => UploadedFile::fake()->create(
                    'request.pdf',
                    10,
                    'application/pdf'
                ),
            ]);

        $response->assertRedirect(route('lesson-requests.create'));

        $path = session('uploaded_lesson_request_file');

        $this->assertNotNull($path);
        Storage::disk('private')->assertExists($path);

        $this->actingAs($student->user)
            ->withSession(['uploaded_lesson_request_file' => $path])
            ->get(route('lesson-requests.create'))
            ->assertOk()
            ->assertSee(route('protected-files.show', ['path' => $path]));
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
