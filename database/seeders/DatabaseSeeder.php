<?php

namespace Database\Seeders;

use App\Enums\ChatSenderRole;
use App\Http\Utility\CartItem;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use App\Services\InvoiceService;
use Dompdf\Dompdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('private')->deleteDirectory('demo');

        $adminPassword = env('SEED_ADMIN_PASSWORD', 'password');
        $studentPassword = env('SEED_STUDENT_PASSWORD', 'password');
        $secondStudentPassword = env('SEED_STUDENT_2_PASSWORD', $studentPassword);

        $adminUser = User::create([
            'name' => env('SEED_ADMIN_NAME', 'Mario'),
            'surname' => env('SEED_ADMIN_SURNAME', 'Rossi'),
            'email' => env('SEED_ADMIN_EMAIL', 'admin@example.com'),
            'email_verified_at' => now(),
            'password' => Hash::make($adminPassword),
            'role' => 'admin',
            'status' => 'active',
        ]);

        Admin::create([
            'user_id' => $adminUser->id,
            'tax_code' => 'RSSMRA80A01H501U',
            'street' => 'Via Roma',
            'house_number' => '10',
            'city' => 'Roma',
            'province' => 'RM',
            'postal_code' => '00100',
            'vat_number' => '12345678901',
        ]);

        $students = collect([
            [
                'name' => env('SEED_STUDENT_NAME', 'Giulia'),
                'surname' => env('SEED_STUDENT_SURNAME', 'Bianchi'),
                'email' => env('SEED_STUDENT_EMAIL', 'student@example.com'),
                'password' => $studentPassword,
                'tax_code' => env('SEED_STUDENT_TAX_CODE', 'BNCGLI90A41H501K'),
            ],
            [
                'name' => env('SEED_STUDENT_2_NAME', 'Luca'),
                'surname' => env('SEED_STUDENT_2_SURNAME', 'Verdi'),
                'email' => env('SEED_STUDENT_2_EMAIL', 'student2@example.com'),
                'password' => $secondStudentPassword,
                'tax_code' => env('SEED_STUDENT_2_TAX_CODE', 'VRDLCU91B12F205Z'),
            ],
        ])->map(function (array $data) {
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($data['password']),
                'role' => 'student',
                'status' => 'active',
            ]);

            return Student::create([
                'user_id' => $user->id,
                'street' => 'Via Garibaldi',
                'house_number' => '5',
                'city' => 'Milano',
                'province' => 'MI',
                'postal_code' => '20100',
                'tax_code' => $data['tax_code'],
            ]);
        });

        $programming = ThemeArea::create(['name' => 'Programming']);
        $databases = ThemeArea::create(['name' => 'Databases']);

        $php = Subject::create(['theme_area_id' => $programming->id, 'name' => 'PHP']);
        $sql = Subject::create(['theme_area_id' => $databases->id, 'name' => 'SQL']);

        $laravel = Course::create(['subject_id' => $php->id, 'name' => 'Laravel basics']);
        $mysql = Course::create(['subject_id' => $sql->id, 'name' => 'MySQL fundamentals']);

        $lesson = Lesson::create([
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

        $paidLesson = Lesson::create([
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

        $advancedLesson = Lesson::create([
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

        $exercise = Exercise::create([
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

        $advancedExercise = Exercise::create([
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

        $lessonRequest = LessonRequest::create([
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

        $secondLessonRequest = LessonRequest::create([
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

        Review::create([
            'student_id' => $students[0]->id,
            'rating' => 5,
            'review' => 'Le spiegazioni sono chiare e molto pratiche.',
        ]);

        $products = [
            [
                'id' => $paidLesson->id,
                'type' => CartItem::LESSON,
                'price' => $paidLesson->price,
                'description' => 'Lesson: '.$paidLesson->title,
            ],
            [
                'id' => $advancedLesson->id,
                'type' => CartItem::LESSON,
                'price' => $advancedLesson->price,
                'description' => 'Lesson: '.$advancedLesson->title,
            ],
            [
                'id' => $exercise->id,
                'type' => CartItem::EXERCISE,
                'price' => $exercise->price,
                'description' => 'Exercise: '.$exercise->title,
            ],
            [
                'id' => $advancedExercise->id,
                'type' => CartItem::EXERCISE,
                'price' => $advancedExercise->price,
                'description' => 'Exercise: '.$advancedExercise->title,
            ],
            [
                'id' => $lessonRequest->id,
                'type' => CartItem::REQUESTED_LESSON,
                'price' => $lessonRequest->price,
                'description' => 'Request: '.$lessonRequest->title,
            ],
            [
                'id' => $secondLessonRequest->id,
                'type' => CartItem::REQUESTED_LESSON,
                'price' => $secondLessonRequest->price,
                'description' => 'Request: '.$secondLessonRequest->title,
            ],
        ];

        $orderPlans = [
            ['student' => 0, 'days_ago' => 1, 'items' => [0, 2]],
            ['student' => 0, 'days_ago' => 4, 'items' => [1]],
            ['student' => 1, 'days_ago' => 7, 'items' => [2, 3]],
            ['student' => 0, 'days_ago' => 12, 'items' => [4]],
            ['student' => 1, 'days_ago' => 15, 'items' => [5]],
            ['student' => 1, 'days_ago' => 22, 'items' => [0, 3]],
            ['student' => 0, 'days_ago' => 35, 'items' => [1, 2]],
            ['student' => 1, 'days_ago' => 43, 'items' => [3]],
            ['student' => 0, 'days_ago' => 58, 'items' => [0, 4]],
            ['student' => 1, 'days_ago' => 71, 'items' => [1, 5]],
            ['student' => 0, 'days_ago' => 96, 'items' => [2]],
            ['student' => 1, 'days_ago' => 124, 'items' => [0, 1, 3]],
        ];

        $invoiceService = app(InvoiceService::class);

        foreach ($orderPlans as $index => $plan) {
            $orderedAt = now()->subDays($plan['days_ago']);
            $order = Order::create([
                'student_id' => $students[$plan['student']]->id,
                'ordered_at' => $orderedAt,
            ]);
            $order->forceFill([
                'created_at' => $orderedAt,
                'updated_at' => $orderedAt,
            ])->save();

            foreach ($plan['items'] as $productIndex) {
                $product = $products[$productIndex];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'product_type' => $product['type'],
                    'price' => $product['price'],
                    'description' => $product['description'],
                ]);
            }

            $invoiceService->generatePdf($order->id);
        }

        $chat = Chat::create([
            'product_id' => $paidLesson->id,
            'product_type' => CartItem::LESSON,
            'student_id' => $students[0]->id,
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'I have a question about this lesson.',
            'sender_role' => ChatSenderRole::STUDENT->value,
            'sent_at' => now()->subHours(2),
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'Certo, dimmi pure.',
            'sender_role' => ChatSenderRole::ADMIN->value,
            'sent_at' => now()->subHour(),
        ]);
    }

    private function storeDemoPdf(string $path, string $title, array $lines): string
    {
        $dompdf = new Dompdf;
        $escapedTitle = e($title);
        $generatedAt = now()->format('d/m/Y H:i');
        $items = collect($lines)
            ->map(fn (string $line) => '<li>'.e($line).'</li>')
            ->implode('');

        $dompdf->loadHtml(<<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{$escapedTitle}</title>
    <style>
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.5; margin: 48px; }
        h1 { color: #0f766e; font-size: 26px; margin-bottom: 8px; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 32px; }
        .box { border: 1px solid #d1d5db; border-radius: 8px; padding: 20px; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>{$escapedTitle}</h1>
    <div class="meta">Demo teaching document generated at {$generatedAt}</div>
    <div class="box"><ul>{$items}</ul></div>
</body>
</html>
HTML);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Storage::disk('private')->put($path, $dompdf->output());

        return $path;
    }
}
