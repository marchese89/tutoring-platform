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

class AdminTeachingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_area_with_subjects_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $themeArea = ThemeArea::factory()->create();
        Subject::factory()->for($themeArea)->create();

        $this->actingAs($admin)
            ->delete(route('admin.theme-areas.destroy', $themeArea->id))
            ->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('theme_areas', ['id' => $themeArea->id]);
    }

    public function test_subject_with_courses_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subject = Subject::factory()->create();
        Course::factory()->for($subject)->create();

        $this->actingAs($admin)
            ->delete(route('admin.subjects.destroy', $subject->id))
            ->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('subjects', ['id' => $subject->id]);
    }

    public function test_course_with_content_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        Lesson::factory()->for($course)->create();
        Exercise::factory()->for($course)->create();

        $this->actingAs($admin)
            ->delete(route('admin.courses.destroy', $course->id))
            ->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_empty_teaching_records_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $themeArea = ThemeArea::factory()->create();
        $subject = Subject::factory()->for($themeArea)->create();
        $course = Course::factory()->for($subject)->create();

        $this->actingAs($admin)
            ->delete(route('admin.courses.destroy', $course->id))
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->delete(route('admin.subjects.destroy', $subject->id))
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->delete(route('admin.theme-areas.destroy', $themeArea->id))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
        $this->assertDatabaseMissing('theme_areas', ['id' => $themeArea->id]);
    }
}
