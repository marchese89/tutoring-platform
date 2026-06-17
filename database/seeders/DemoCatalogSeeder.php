<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\ThemeArea;
use Database\Seeders\Concerns\CreatesDemoPdfs;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    use CreatesDemoPdfs;

    public function run(): void
    {
        $programming = ThemeArea::create(['name' => 'Programming']);
        $databases = ThemeArea::create(['name' => 'Databases']);

        $php = Subject::create(['theme_area_id' => $programming->id, 'name' => 'PHP']);
        $sql = Subject::create(['theme_area_id' => $databases->id, 'name' => 'SQL']);

        $laravel = Course::create(['subject_id' => $php->id, 'name' => 'Laravel basics']);
        $mysql = Course::create(['subject_id' => $sql->id, 'name' => 'MySQL fundamentals']);

        Lesson::create([
            'course_id' => $laravel->id,
            'number' => 1,
            'title' => 'Routes and controllers',
            'presentation_file' => $this->storeDemoPdf('demo/lessons/routes-presentation.pdf', 'Routes and controllers', [
                'Route groups and route names',
                'Controller responsibilities',
                'Public and authenticated route separation',
            ]),
            'content_file' => $this->storeDemoPdf('demo/lessons/routes-content.pdf', 'Routes and controllers - notes', [
                'Keep route names stable and readable.',
                'Use controllers to prepare data for Blade views.',
                'Avoid queries directly inside templates.',
            ]),
            'price' => 0,
        ]);

        Lesson::create([
            'course_id' => $laravel->id,
            'number' => 2,
            'title' => 'Blade components',
            'presentation_file' => $this->storeDemoPdf('demo/lessons/blade-components-presentation.pdf', 'Blade components', [
                'Reusable layout sections',
                'Table cards and empty states',
                'Form controls and validation feedback',
            ]),
            'content_file' => $this->storeDemoPdf('demo/lessons/blade-components-content.pdf', 'Blade components - paid lesson', [
                'Extract repeated markup into named components.',
                'Keep component APIs small and explicit.',
                'Use consistent spacing and heading hierarchy.',
            ]),
            'price' => 15,
        ]);

        Lesson::create([
            'course_id' => $mysql->id,
            'number' => 1,
            'title' => 'Indexes and query plans',
            'presentation_file' => $this->storeDemoPdf('demo/lessons/mysql-indexes-presentation.pdf', 'Indexes and query plans', [
                'Reading EXPLAIN output',
                'Choosing useful indexes',
                'Avoiding unnecessary full table scans',
            ]),
            'content_file' => $this->storeDemoPdf('demo/lessons/mysql-indexes-content.pdf', 'Indexes and query plans - paid lesson', [
                'Use indexes on columns involved in joins and filters.',
                'Measure query plans before and after the change.',
                'Keep indexes aligned with real application queries.',
            ]),
            'price' => 20,
        ]);

        Exercise::create([
            'course_id' => $mysql->id,
            'title' => 'Select queries',
            'prompt_file' => $this->storeDemoPdf('demo/exercises/select-queries-prompt.pdf', 'Exercise - Select queries', [
                'Write a query that lists paid orders by student.',
                'Include order date, student email and total amount.',
                'Sort the results from newest to oldest.',
            ]),
            'solution_file' => $this->storeDemoPdf('demo/exercises/select-queries-solution.pdf', 'Solution - Select queries', [
                'Join orders, students, users and order_items.',
                'Group by order and aggregate item prices.',
                'Use aliases that describe the output columns.',
            ]),
            'price' => 10,
        ]);

        Exercise::create([
            'course_id' => $laravel->id,
            'title' => 'Refactor a controller',
            'prompt_file' => $this->storeDemoPdf('demo/exercises/refactor-controller-prompt.pdf', 'Exercise - Refactor a controller', [
                'Move data preparation from Blade to the controller.',
                'Preserve the current route names.',
                'Return a clean view model to the template.',
            ]),
            'solution_file' => $this->storeDemoPdf('demo/exercises/refactor-controller-solution.pdf', 'Solution - Refactor a controller', [
                'Use eager loading for related records.',
                'Map database rows into display-ready structures.',
                'Keep the Blade file focused on markup.',
            ]),
            'price' => 18,
        ]);
    }
}
