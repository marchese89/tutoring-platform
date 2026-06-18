<?php

namespace Database\Seeders;

use App\Http\Utility\CartItem;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        $studentEmails = [
            env('SEED_STUDENT_EMAIL', 'student@example.com'),
            env('SEED_STUDENT_2_EMAIL', 'student2@example.com'),
        ];

        $students = collect($studentEmails)
            ->map(fn (string $email) => User::where('email', $email)->with('student')->firstOrFail()->student)
            ->values();

        $paidLesson = Lesson::where('title', 'Blade components')->firstOrFail();
        $advancedLesson = Lesson::where('title', 'Indexes and query plans')->firstOrFail();
        $exercise = Exercise::where('title', 'Select queries')->firstOrFail();
        $advancedExercise = Exercise::where('title', 'Refactor a controller')->firstOrFail();
        $lessonRequest = LessonRequest::where('title', 'Custom Laravel refactoring lesson')->firstOrFail();
        $secondLessonRequest = LessonRequest::where('title', 'SQL exercise correction')->firstOrFail();

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

        foreach ($orderPlans as $plan) {
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
    }
}
