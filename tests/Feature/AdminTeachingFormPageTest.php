<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTeachingFormPageTest extends TestCase
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
                'locale' => 'en',
                'uploaded_lesson_presentation' => $presentationPath,
                'uploaded_lesson_content' => $contentPath,
            ])
            ->get(route('admin.lessons.create', $course->id))
            ->assertOk()
            ->assertSee('New lesson')
            ->assertSee('Presentation')
            ->assertSee('Solution')
            ->assertSee('Lesson details')
            ->assertSee('Add lesson')
            ->assertSee(route('protected-files.show', ['path' => $presentationPath]))
            ->assertSee(route('protected-files.show', ['path' => $contentPath]))
            ->assertSee('pdf-viewer--compact', false)
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
                'locale' => 'en',
                'uploaded_exercise_prompt' => $promptPath,
                'uploaded_exercise_solution' => $solutionPath,
            ])
            ->get(route('admin.exercises.create', $course->id))
            ->assertOk()
            ->assertSee('New exercise')
            ->assertSee('Prompt')
            ->assertSee('Solution')
            ->assertSee('Exercise details')
            ->assertSee('Add exercise')
            ->assertSee(route('protected-files.show', ['path' => $promptPath]))
            ->assertSee(route('protected-files.show', ['path' => $solutionPath]))
            ->assertSee('pdf-viewer--compact', false)
            ->assertSee(route('admin.exercises.store'));
    }

    public function test_lesson_edit_page_uses_shared_document_upload_forms(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $lesson = Lesson::factory()->for($course)->create([
            'presentation_file' => 'lessons/presentations/lesson.pdf',
            'content_file' => 'lessons/files/lesson.pdf',
        ]);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.lessons.edit', [$course->id, $lesson->id]))
            ->assertOk()
            ->assertSee('Edit lesson')
            ->assertSee('Presentation')
            ->assertSee('Solution')
            ->assertSee('Lesson details')
            ->assertSee('Save changes')
            ->assertSee(route('protected-files.show', [
                'path' => $lesson->presentation_file,
            ]))
            ->assertSee(route('protected-files.show', [
                'path' => $lesson->content_file,
            ]))
            ->assertSee(route('admin.lessons.presentation.update', $lesson->id))
            ->assertSee(route('admin.lessons.file.update', $lesson->id))
            ->assertSee(route('admin.lessons.update', $lesson->id));
    }

    public function test_exercise_edit_page_uses_shared_document_upload_forms(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $exercise = Exercise::factory()->for($course)->create([
            'prompt_file' => 'exercises/trace/exercise.pdf',
            'solution_file' => 'exercises/execution/exercise.pdf',
        ]);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.exercises.edit', [$course->id, $exercise->id]))
            ->assertOk()
            ->assertSee('Edit exercise')
            ->assertSee('Prompt')
            ->assertSee('Solution')
            ->assertSee('Exercise details')
            ->assertSee('Save changes')
            ->assertSee(route('protected-files.show', [
                'path' => $exercise->prompt_file,
            ]))
            ->assertSee(route('protected-files.show', [
                'path' => $exercise->solution_file,
            ]))
            ->assertSee(route('admin.exercises.trace.update', $exercise->id))
            ->assertSee(route('admin.exercises.execution.update', $exercise->id))
            ->assertSee(route('admin.exercises.update', $exercise->id));
    }

    public function test_nested_teaching_routes_reject_content_from_another_course(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = $this->createCourse();
        $otherCourse = $this->createCourse();
        $lesson = Lesson::factory()->for($otherCourse)->create();
        $exercise = Exercise::factory()->for($otherCourse)->create();

        $this->actingAs($admin)
            ->get(route('admin.lessons.edit', [$course, $lesson]))
            ->assertNotFound();

        $this->actingAs($admin)
            ->get(route('admin.exercises.edit', [$course, $exercise]))
            ->assertNotFound();
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
