<?php

namespace Tests\Feature;

use App\Enums\PaymentPurpose;
use App\Http\Utility\CartItem;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Exercise;
use App\Models\Invoice;
use App\Models\InvoiceSequence;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_factory_creates_student_user(): void
    {
        $student = Student::factory()->create();

        $this->assertSame('student', $student->user->role);
        $this->assertDatabaseHas('students', ['user_id' => $student->user->id]);
    }

    public function test_admin_factory_creates_admin_user(): void
    {
        $admin = Admin::factory()->create();

        $this->assertSame('admin', $admin->user->role);
        $this->assertDatabaseHas('admins', ['user_id' => $admin->user->id]);
    }

    public function test_lesson_factory_creates_lesson(): void
    {
        $lesson = Lesson::factory()->create();

        $this->assertDatabaseHas('lessons', ['id' => $lesson->id]);
        $this->assertNotNull($lesson->course);
        $this->assertNotNull($lesson->course->subject);
        $this->assertNotNull($lesson->course->subject->themeArea);
    }

    public function test_exercise_factory_creates_exercise(): void
    {
        $exercise = Exercise::factory()->create();

        $this->assertDatabaseHas('exercises', ['id' => $exercise->id]);
        $this->assertNotNull($exercise->course);
        $this->assertNotNull($exercise->course->subject);
        $this->assertNotNull($exercise->course->subject->themeArea);
    }

    public function test_order_factory_creates_order_for_student(): void
    {
        $order = Order::factory()->create();

        $this->assertNotNull($order->student);
        $this->assertSame('student', $order->student->user->role);
    }

    public function test_order_item_factory_creates_item_for_order(): void
    {
        $orderItem = OrderItem::factory()->create();

        $this->assertNotNull($orderItem->order);
        $this->assertNotNull($orderItem->order->student);
    }

    public function test_lesson_request_factory_creates_request_for_student(): void
    {
        $request = LessonRequest::factory()->create();

        $this->assertNotNull($request->student);
        $this->assertSame('student', $request->student->user->role);
    }

    public function test_chat_factory_creates_chat_for_lesson_and_student(): void
    {
        $chat = Chat::factory()->create();

        $this->assertNotNull($chat->student);
        $this->assertDatabaseHas('lessons', ['id' => $chat->product_id]);
        $this->assertSame(CartItem::LESSON, $chat->product_type);
    }

    public function test_chat_message_factory_creates_message_for_chat(): void
    {
        $message = ChatMessage::factory()->create();

        $this->assertNotNull($message->chat);
        $this->assertNotNull($message->chat->student);
    }

    public function test_certificate_factory_creates_certificate(): void
    {
        $certificate = Certificate::factory()->create();

        $this->assertNotNull($certificate->name);
        $this->assertStringEndsWith('.pdf', $certificate->file_path);
    }

    public function test_review_factory_creates_review_for_student(): void
    {
        $review = Review::factory()->create();

        $this->assertNotNull($review->student);
        $this->assertSame('student', $review->student->user->role);
        $this->assertGreaterThanOrEqual(1, $review->rating);
        $this->assertLessThanOrEqual(5, $review->rating);
    }

    public function test_payment_transaction_factory_creates_transaction_for_user(): void
    {
        $transaction = PaymentTransaction::factory()->create();

        $this->assertNotNull($transaction->user);
        $this->assertSame('student', $transaction->user->role);
        $this->assertSame(PaymentPurpose::CHECKOUT, $transaction->purpose);
        $this->assertSame('eur', $transaction->currency);
        $this->assertNull($transaction->order);
        $this->assertFalse($transaction->isCompleted());
    }

    public function test_invoice_factory_creates_direct_invoice_for_student(): void
    {
        $invoice = Invoice::factory()->create();

        $this->assertNotNull($invoice->student);
        $this->assertSame('student', $invoice->student->user->role);
        $this->assertSame('extra', $invoice->source);
        $this->assertSame('eur', $invoice->currency);
        $this->assertIsArray($invoice->customer_snapshot);
        $this->assertIsArray($invoice->line_items);
    }

    public function test_invoice_sequence_factory_creates_yearly_sequence(): void
    {
        $sequence = InvoiceSequence::factory()->create();

        $this->assertNotNull($sequence->year);
        $this->assertGreaterThanOrEqual(0, $sequence->last_number);
        $this->assertDatabaseHas('invoice_sequences', [
            'year' => $sequence->year,
            'last_number' => $sequence->last_number,
        ]);
    }
}
