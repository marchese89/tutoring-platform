<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_request_rejects_non_pdf_files(): void
    {
        Storage::fake('private');
        $student = $this->createStudent();

        $response = $this->actingAs($student->user)
            ->from(route('lesson-requests.create'))
            ->post(route('lesson-requests.files.store'), [
                'file' => UploadedFile::fake()->create(
                    'request.txt',
                    10,
                    'text/plain'
                ),
            ]);

        $response->assertRedirect(route('lesson-requests.create'));
        $response->assertSessionHasErrors('file');
        $this->assertSame([], Storage::disk('private')->allFiles());
    }

    public function test_admin_teaching_uploads_reject_non_pdf_files(): void
    {
        Storage::fake('private');
        $admin = User::factory()->create(['role' => 'admin']);
        $invalidFile = UploadedFile::fake()->create(
            'payload.txt',
            10,
            'text/plain'
        );

        $lessonResponse = $this->actingAs($admin)
            ->post(route('admin.lessons.upload-presentation.store'), [
                'presentation_file' => $invalidFile,
            ]);
        $lessonResponse->assertSessionHasErrors('presentation_file');

        $exerciseResponse = $this->actingAs($admin)
            ->post(route('admin.exercises.trace.upload.store'), [
                'prompt_file' => UploadedFile::fake()->create(
                    'payload.txt',
                    10,
                    'text/plain'
                ),
            ]);
        $exerciseResponse->assertSessionHasErrors('prompt_file');
    }

    public function test_admin_certificate_upload_rejects_non_pdf_files(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.account.certificates.uploads.store'), [
                'file' => UploadedFile::fake()->create(
                    'certificate.txt',
                    10,
                    'text/plain'
                ),
            ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_admin_photo_rejects_non_image_files(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.account.photo.update'), [
                'file' => UploadedFile::fake()->create(
                    'photo.pdf',
                    10,
                    'application/pdf'
                ),
            ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_pdf_larger_than_configured_limit_is_rejected(): void
    {
        config(['uploads.pdf_max_kilobytes' => 1]);
        Storage::fake('private');
        $student = $this->createStudent();

        $response = $this->actingAs($student->user)
            ->post(route('lesson-requests.files.store'), [
                'file' => UploadedFile::fake()->create(
                    'request.pdf',
                    2,
                    'application/pdf'
                ),
            ]);

        $response->assertSessionHasErrors('file');
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
