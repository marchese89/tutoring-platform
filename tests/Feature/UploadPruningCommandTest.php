<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadPruningCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_prunes_unreferenced_uploads_without_deleting_referenced_files(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $course = $this->createCourse();
        Storage::disk('private')->put('lessons/files/referenced.pdf', 'referenced');
        Storage::disk('private')->put('lessons/files/orphan.pdf', 'orphan');
        Storage::disk('public')->put('admin/photos/referenced.jpg', 'referenced');
        Storage::disk('public')->put('admin/photos/orphan.jpg', 'orphan');
        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Referenced lesson',
            'number' => 1,
            'content_file' => 'lessons/files/referenced.pdf',
            'price' => 0,
        ]);
        $admin = User::factory()->create(['role' => 'admin']);
        Admin::create([
            'user_id' => $admin->id,
            'photo_path' => '/storage/admin/photos/referenced.jpg',
        ]);

        $this->artisan('uploads:prune-orphans', ['--hours' => 0])
            ->expectsOutput('Deleted 2 unreferenced upload files.')
            ->assertExitCode(0);

        Storage::disk('private')->assertExists('lessons/files/referenced.pdf');
        Storage::disk('private')->assertMissing('lessons/files/orphan.pdf');
        Storage::disk('public')->assertExists('admin/photos/referenced.jpg');
        Storage::disk('public')->assertMissing('admin/photos/orphan.jpg');
    }

    public function test_dry_run_reports_without_deleting_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('certificates/orphan.pdf', 'orphan');

        $this->artisan('uploads:prune-orphans', ['--hours' => 0, '--dry-run' => true])
            ->expectsOutput('Would delete 1 unreferenced upload files.')
            ->assertExitCode(0);

        Storage::disk('public')->assertExists('certificates/orphan.pdf');
    }

    public function test_referenced_certificate_is_not_pruned(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('certificates/referenced.pdf', 'referenced');
        Certificate::create([
            'name' => 'Referenced certificate',
            'file_path' => '/storage/certificates/referenced.pdf',
        ]);

        $this->artisan('uploads:prune-orphans', ['--hours' => 0])
            ->expectsOutput('Deleted 0 unreferenced upload files.')
            ->assertExitCode(0);

        Storage::disk('public')->assertExists('certificates/referenced.pdf');
    }

    private function createCourse(): Course
    {
        $themeArea = ThemeArea::create(['name' => fake()->unique()->word()]);
        $subject = Subject::create([
            'theme_area_id' => $themeArea->id,
            'name' => fake()->unique()->word(),
        ]);

        return Course::create([
            'subject_id' => $subject->id,
            'name' => fake()->unique()->words(2, true),
        ]);
    }
}
