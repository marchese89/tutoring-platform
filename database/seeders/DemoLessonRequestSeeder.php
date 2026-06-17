<?php

namespace Database\Seeders;

use App\Models\LessonRequest;
use App\Models\User;
use Database\Seeders\Concerns\CreatesDemoPdfs;
use Illuminate\Database\Seeder;

class DemoLessonRequestSeeder extends Seeder
{
    use CreatesDemoPdfs;

    public function run(): void
    {
        $studentEmails = [
            env('SEED_STUDENT_EMAIL', 'student@example.com'),
            env('SEED_STUDENT_2_EMAIL', 'student2@example.com'),
        ];

        $students = collect($studentEmails)
            ->map(fn (string $email) => User::where('email', $email)->with('student')->firstOrFail()->student)
            ->values();

        LessonRequest::create([
            'student_id' => $students[0]->id,
            'title' => 'Custom Laravel refactoring lesson',
            'request_file' => $this->storeDemoPdf('demo/requests/laravel-refactoring-request.pdf', 'Request - Laravel refactoring', [
                'Review route naming and controller boundaries.',
                'Identify Blade files with database queries.',
                'Suggest a safe refactoring order.',
            ]),
            'solution_file' => $this->storeDemoPdf('demo/requests/laravel-refactoring-solution.pdf', 'Solution - Laravel refactoring', [
                'Create route groups by application area.',
                'Move query logic into dedicated controller methods.',
                'Extract repeated dashboard markup into components.',
            ]),
            'price' => 25,
            'is_fulfilled' => true,
            'is_paid' => true,
            'requested_at' => now()->subDays(3),
        ]);

        LessonRequest::create([
            'student_id' => $students[1]->id,
            'title' => 'SQL exercise correction',
            'request_file' => $this->storeDemoPdf('demo/requests/sql-correction-request.pdf', 'Request - SQL correction', [
                'Check aggregate query correctness.',
                'Verify joins across orders and order items.',
                'Explain why totals differ between attempts.',
            ]),
            'solution_file' => $this->storeDemoPdf('demo/requests/sql-correction-solution.pdf', 'Solution - SQL correction', [
                'The aggregate must group by order id.',
                'Use SUM(order_items.price) for the order total.',
                'Filter dates with a range instead of formatting the column.',
            ]),
            'price' => 30,
            'is_fulfilled' => true,
            'is_paid' => true,
            'requested_at' => now()->subDays(12),
        ]);
    }
}
