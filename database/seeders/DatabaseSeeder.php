<?php

namespace Database\Seeders;

use App\Http\Utility\CartItem;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ThemeArea;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
            'price' => 0,
        ]);

        $paidLesson = Lesson::create([
            'course_id' => $laravel->id,
            'number' => 2,
            'title' => 'Blade components',
            'price' => 15,
        ]);

        $exercise = Exercise::create([
            'course_id' => $mysql->id,
            'title' => 'Select queries',
            'price' => 10,
        ]);

        $lessonRequest = LessonRequest::create([
            'student_id' => $students[0]->id,
            'title' => 'Custom Laravel refactoring lesson',
            'price' => 25,
            'is_fulfilled' => true,
            'is_paid' => true,
            'requested_at' => now()->subDays(3),
        ]);

        Review::create([
            'student_id' => $students[0]->id,
            'rating' => 5,
            'review' => 'Le spiegazioni sono chiare e molto pratiche.',
        ]);

        $order = Order::create([
            'student_id' => $students[0]->id,
            'ordered_at' => now()->subDay(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $paidLesson->id,
            'product_type' => CartItem::LESSON,
            'price' => $paidLesson->price,
            'description' => 'Lesson: ' . $paidLesson->title,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $lessonRequest->id,
            'product_type' => CartItem::REQUESTED_LESSON,
            'price' => $lessonRequest->price,
            'description' => 'Request: ' . $lessonRequest->title,
        ]);

        Invoice::create([
            'number' => 1,
            'issued_at' => now()->subDay(),
            'order_id' => $order->id,
        ]);

        $chat = Chat::create([
            'product_id' => $paidLesson->id,
            'product_type' => CartItem::LESSON,
            'student_id' => $students[0]->id,
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'Ho una domanda sulla lezione.',
            'sender_role' => 0,
            'sent_at' => now()->subHours(2),
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => 'Certo, dimmi pure.',
            'sender_role' => 1,
            'sent_at' => now()->subHour(),
        ]);
    }
}
