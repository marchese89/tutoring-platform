<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Subject;
use App\Models\ThemeArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_lists_are_paginated(): void
    {
        for ($index = 1; $index <= 13; $index++) {
            $themeArea = ThemeArea::factory()->create([
                'name' => sprintf('Public area %02d', $index),
            ]);
            Subject::factory()->for($themeArea)->create();
        }

        $subjectArea = ThemeArea::factory()->create(['name' => 'Subject catalog']);

        for ($index = 1; $index <= 13; $index++) {
            Subject::factory()->for($subjectArea)->create([
                'name' => sprintf('Public subject %02d', $index),
            ]);
        }

        $courseSubject = Subject::factory()
            ->for($subjectArea)
            ->create(['name' => 'Course catalog']);

        for ($index = 1; $index <= 13; $index++) {
            Course::factory()->for($courseSubject)->create([
                'name' => sprintf('Public course %02d', $index),
            ]);
        }

        $this->get(route('theme-areas.index'))
            ->assertOk()
            ->assertSee('Public area 01')
            ->assertDontSee('Public area 13')
            ->assertSee('page=2', false);

        $this->get(route('subjects.index', $subjectArea))
            ->assertOk()
            ->assertSee('Public subject 01')
            ->assertDontSee('Public subject 13')
            ->assertSee('page=2', false);

        $this->get(route('courses.index', $courseSubject))
            ->assertOk()
            ->assertSee('Public course 01')
            ->assertDontSee('Public course 13')
            ->assertSee('page=2', false);
    }
}
