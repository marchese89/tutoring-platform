<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLessonPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_content_uses_shared_pdf_viewer(): void
    {
        $course = Course::factory()->create(['name' => 'Programming']);
        $lesson = Lesson::factory()->for($course)->create([
            'title' => 'Algorithms',
            'content_file' => 'lessons/files/algorithms.pdf',
        ]);

        $this->get(route('lessons.show', [$course, $lesson]))
            ->assertOk()
            ->assertSee('Algorithms')
            ->assertSee('src="/protected-files/lessons/files/algorithms.pdf#view=FitH"', false)
            ->assertSee('class="pdf-viewer"', false);
    }

    public function test_lesson_routes_return_not_found_when_content_does_not_belong_to_course(): void
    {
        $course = Course::factory()->create();
        $otherCourse = Course::factory()->create();
        $lesson = Lesson::factory()->for($otherCourse)->create();

        $this->get(route('lessons.presentation', [$course, $lesson]))
            ->assertNotFound();

        $this->get(route('lessons.show', [$course, $lesson]))
            ->assertNotFound();
    }

    public function test_exercise_trace_returns_not_found_when_content_does_not_belong_to_course(): void
    {
        $course = Course::factory()->create();
        $otherCourse = Course::factory()->create();
        $exercise = Exercise::factory()->for($otherCourse)->create();

        $this->get(route('exercises.trace', [$course, $exercise]))
            ->assertNotFound();
    }
}
