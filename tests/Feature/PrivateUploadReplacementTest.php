<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrivateUploadReplacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_replacing_exercise_file_stores_new_file_and_deletes_old_one(): void
    {
        Storage::fake('private');
        Storage::disk('private')->put('exercises/trace/old.pdf', 'old');
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $exercise = Exercise::create([
            'course_id' => $course->id,
            'title' => 'Exercise',
            'prompt_file' => 'exercises/trace/old.pdf',
            'price' => 10,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.exercises.trace.update', $exercise), [
                'prompt_file' => UploadedFile::fake()->create(
                    'replacement.pdf',
                    10,
                    'application/pdf'
                ),
            ]);

        $response->assertRedirect();
        Storage::disk('private')->assertMissing('exercises/trace/old.pdf');

        $newPath = $exercise->fresh()->prompt_file;

        $this->assertNotSame('exercises/trace/old.pdf', $newPath);
        Storage::disk('private')->assertExists($newPath);
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
