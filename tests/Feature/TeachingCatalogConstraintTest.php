<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\ThemeArea;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeachingCatalogConstraintTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_area_names_are_unique(): void
    {
        ThemeArea::factory()->create(['name' => 'Programming']);

        $this->expectException(QueryException::class);

        ThemeArea::factory()->create(['name' => 'Programming']);
    }

    public function test_subject_names_are_unique_inside_the_same_theme_area(): void
    {
        $themeArea = ThemeArea::factory()->create();

        Subject::factory()->create([
            'theme_area_id' => $themeArea->id,
            'name' => 'PHP',
        ]);

        $this->expectException(QueryException::class);

        Subject::factory()->create([
            'theme_area_id' => $themeArea->id,
            'name' => 'PHP',
        ]);
    }

    public function test_course_names_are_unique_inside_the_same_subject(): void
    {
        $subject = Subject::factory()->create();

        Course::factory()->create([
            'subject_id' => $subject->id,
            'name' => 'Laravel basics',
        ]);

        $this->expectException(QueryException::class);

        Course::factory()->create([
            'subject_id' => $subject->id,
            'name' => 'Laravel basics',
        ]);
    }

    public function test_lesson_numbers_are_unique_inside_the_same_course(): void
    {
        $course = Course::factory()->create();

        Lesson::factory()->create([
            'course_id' => $course->id,
            'number' => 1,
        ]);

        $this->expectException(QueryException::class);

        Lesson::factory()->create([
            'course_id' => $course->id,
            'number' => 1,
        ]);
    }

    public function test_exercise_titles_are_unique_inside_the_same_course(): void
    {
        $course = Course::factory()->create();

        Exercise::factory()->create([
            'course_id' => $course->id,
            'title' => 'Select queries',
        ]);

        $this->expectException(QueryException::class);

        Exercise::factory()->create([
            'course_id' => $course->id,
            'title' => 'Select queries',
        ]);
    }
}
