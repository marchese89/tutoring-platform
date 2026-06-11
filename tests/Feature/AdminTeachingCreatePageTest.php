<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTeachingCreatePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_create_page_uses_prepared_upload_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $presentationPath = 'lessons/presentations/draft.pdf';
        $contentPath = 'lessons/files/draft.pdf';

        $this->actingAs($admin)
            ->withSession([
                'uploaded_lesson_presentation' => $presentationPath,
                'uploaded_lesson_content' => $contentPath,
            ])
            ->get(route('admin.lessons.create', $course->id))
            ->assertOk()
            ->assertSee(route('protected-files.show', ['path' => $presentationPath]))
            ->assertSee(route('protected-files.show', ['path' => $contentPath]))
            ->assertSee(route('admin.lessons.store'));
    }

    public function test_exercise_create_page_uses_prepared_upload_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $promptPath = 'exercises/trace/draft.pdf';
        $solutionPath = 'exercises/execution/draft.pdf';

        $this->actingAs($admin)
            ->withSession([
                'uploaded_exercise_prompt' => $promptPath,
                'uploaded_exercise_solution' => $solutionPath,
            ])
            ->get(route('admin.exercises.create', $course->id))
            ->assertOk()
            ->assertSee(route('protected-files.show', ['path' => $promptPath]))
            ->assertSee(route('protected-files.show', ['path' => $solutionPath]))
            ->assertSee(route('admin.exercises.store'));
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
