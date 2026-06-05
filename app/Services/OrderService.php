<?php

namespace App\Services;

use App\Http\Utility\Cart;
use App\Http\Utility\CartItem;
use App\Models\Chat;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;

class OrderService
{
    public function process(Student $student, Cart $cart): int
    {
        $items = array_map(fn (CartItem $item) => [
            'id' => $item->id(),
            'type' => $item->type(),
            'price' => $item->price(),
        ], $cart->items());

        return $this->processSnapshot($student, $items);
    }

    public function processSnapshot(Student $student, array $items): int
    {
        $order = Order::create([
            'student_id' => $student->id,
        ]);

        foreach ($items as $item) {
            $this->handleItem(
                (int) $item['id'],
                (int) $item['type'],
                (int) $item['price'],
                $order->id,
                $student->id
            );
        }

        return $order->id;
    }

    private function handleItem(
        int $productId,
        int $type,
        int $price,
        int $orderId,
        int $studentId
    ) {
        return match ($type) {

            CartItem::LESSON => $this->handleLesson($productId, $price, $orderId, $studentId),

            CartItem::EXERCISE => $this->handleExercise($productId, $price, $orderId, $studentId),

            CartItem::REQUESTED_LESSON => $this->handleRequest($productId, $price, $orderId, $studentId),

            CartItem::COURSE_LESSONS => $this->handleLessonsOfCourse($productId, $orderId, $studentId),

            CartItem::COURSE_EXERCISES => $this->handleExercisesOfCourse($productId, $orderId, $studentId),

            CartItem::FULL_COURSE => $this->handleFullCourse($productId, $orderId, $studentId),

            default => null,
        };
    }

    private function handleLessonsOfCourse($courseId, $orderId, $studentId)
    {
        $lessons = Lesson::where('course_id', $courseId)->get();
        foreach ($lessons as $l) {
            $this->handleLesson($l->id, $l->price, $orderId, $studentId);
        }
    }

    private function handleExercisesOfCourse($courseId, $orderId, $studentId)
    {
        $exs = Exercise::where('course_id', $courseId)->get();
        foreach ($exs as $e) {
            $this->handleExercise($e->id, $e->price, $orderId, $studentId);
        }
    }

    private function handleFullCourse($courseId, $orderId, $studentId)
    {
        $this->handleLessonsOfCourse($courseId, $orderId, $studentId);
        $this->handleExercisesOfCourse($courseId, $orderId, $studentId);
    }

    private function handleLesson(int $id, int $price, int $orderId, int $studentId)
    {
        Lesson::findOrFail($id);
        $this->createOrderItem($orderId, $id, CartItem::LESSON, $price);
        $this->createChat($id, 0, $studentId);
    }

    private function handleExercise(int $id, int $price, int $orderId, int $studentId)
    {
        Exercise::findOrFail($id);
        $this->createOrderItem($orderId, $id, CartItem::EXERCISE, $price);
        $this->createChat($id, 2, $studentId);
    }

    private function handleRequest(int $id, int $price, int $orderId, int $studentId)
    {
        $req = LessonRequest::where('student_id', $studentId)->findOrFail($id);
        $req->update(['is_paid' => 1]);
        $this->createOrderItem($orderId, $id, CartItem::REQUESTED_LESSON, $price);
        $this->createChat($id, 5, $studentId);
    }

    private function createOrderItem(int $orderId, int $productId, int $type, int $price)
    {
        OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'product_type' => $type,
            'price' => $price,
            'description' => match ($type) {
                CartItem::LESSON => 'Lesson: '.Lesson::find($productId)->title,
                CartItem::EXERCISE => 'Exercise: '.Exercise::find($productId)->title,
                CartItem::REQUESTED_LESSON => 'Request: '.LessonRequest::find($productId)->title,
                default => 'Unknown product',
            },
        ]);
    }

    private function createChat(int $productId, int $type, int $studentId)
    {
        Chat::firstOrCreate([
            'product_id' => $productId,
            'product_type' => $type,
            'student_id' => $studentId,
        ]);
    }
}
