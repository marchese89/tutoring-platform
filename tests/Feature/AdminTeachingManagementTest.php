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

    public function test_teaching_management_pages_follow_the_session_locale(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.teaching.index'))
            ->assertOk()
            ->assertSee('Teaching')
            ->assertSee('Topic areas')
            ->assertSee('Course list');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.theme-areas.index'))
            ->assertOk()
            ->assertSee('New topic area')
            ->assertSee('No topic areas found.');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.subjects.index'))
            ->assertOk()
            ->assertSee('New subject')
            ->assertSee('No subjects found.');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('admin.courses.create'))
            ->assertOk()
            ->assertSee('New course')
            ->assertSee('No courses found.');
    }

    public function test_course_list_displays_courses_in_name_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subject = Subject::factory()->create();
        $secondCourse = Course::factory()->for($subject)->create([
            'name' => 'Second course',
        ]);
        $firstCourse = Course::factory()->for($subject)->create([
            'name' => 'First course',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.courses.index'))
            ->assertOk()
            ->assertSeeInOrder([$firstCourse->name, $secondCourse->name])
            ->assertSee(route('admin.courses.edit', $firstCourse->id))
            ->assertSee(route('admin.courses.edit', $secondCourse->id));
    }

    public function test_course_edit_page_displays_content_management_actions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->for($course)->create();
        $exercise = Exercise::factory()->for($course)->create();

        $this->actingAs($admin)
            ->get(route('admin.courses.edit', $course->id))
            ->assertOk()
            ->assertSee($lesson->title)
            ->assertSee($exercise->title)
            ->assertSee(route('admin.lessons.create', $course->id))
            ->assertSee(route('admin.exercises.create', $course->id))
            ->assertSee(route('admin.lessons.edit', [
                'course' => $course->id,
                'lesson' => $lesson->id,
            ]))
            ->assertSee(route('admin.exercises.edit', [
                'course' => $course->id,
                'exercise' => $exercise->id,
            ]));
    }

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

    public function test_duplicate_teaching_catalog_values_return_validation_errors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $themeArea = ThemeArea::factory()->create(['name' => 'Programming']);
        $subject = Subject::factory()->for($themeArea)->create(['name' => 'PHP']);
        $course = Course::factory()->for($subject)->create(['name' => 'Laravel basics']);
        Lesson::factory()->for($course)->create(['number' => 1]);
        Exercise::factory()->for($course)->create(['title' => 'Select queries']);

        $this->actingAs($admin)
            ->post(route('admin.theme-areas.store'), ['name' => 'Programming'])
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)
            ->post(route('admin.subjects.store'), [
                'theme_area_id' => $themeArea->id,
                'name' => 'PHP',
            ])
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)
            ->post(route('admin.courses.store'), [
                'subject_id' => $subject->id,
                'name' => 'Laravel basics',
            ])
            ->assertSessionHasErrors('name');

        $this->actingAs($admin)
            ->post(route('admin.lessons.store'), [
                'course_id' => $course->id,
                'number' => 1,
                'title' => 'Duplicate lesson',
                'price' => 15,
            ])
            ->assertSessionHasErrors('number');

        $this->actingAs($admin)
            ->post(route('admin.exercises.store'), [
                'course_id' => $course->id,
                'title' => 'Select queries',
                'price' => 10,
            ])
            ->assertSessionHasErrors('title');
    }

    public function test_teaching_catalog_records_can_keep_their_current_unique_value_when_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $themeArea = ThemeArea::factory()->create(['name' => 'Programming']);
        $subject = Subject::factory()->for($themeArea)->create(['name' => 'PHP']);
        $course = Course::factory()->for($subject)->create(['name' => 'Laravel basics']);
        $lesson = Lesson::factory()->for($course)->create([
            'number' => 1,
            'title' => 'Routes',
            'price' => 15,
        ]);
        $exercise = Exercise::factory()->for($course)->create([
            'title' => 'Select queries',
            'price' => 10,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.theme-areas.update', $themeArea), ['name' => 'Programming'])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->put(route('admin.subjects.update', $subject), ['name' => 'PHP'])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->put(route('admin.courses.update', $course), ['name' => 'Laravel basics'])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->put(route('admin.lessons.update', $lesson), [
                'number' => 1,
                'title' => 'Routes',
                'price' => 15,
            ])
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->put(route('admin.exercises.update', $exercise), [
                'title' => 'Select queries',
                'price' => 10,
            ])
            ->assertSessionHasNoErrors();
    }
}
