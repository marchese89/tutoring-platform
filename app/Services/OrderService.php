<?php

namespace App\Services;

use App\Http\Utility\Cart;
use App\Http\Utility\CartItem;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\LessonOnRequest;
use App\Models\Chat;
use App\Models\Student;


class OrderService
{
    public function process(Student $student, Cart $cart): int
    {

        $order = Order::create([
            'student_id' => $student->id,
        ]);

        $items = $cart->items();

        foreach ($items as $item) {
            $this->handleItem($item, $order->id, $student->id);
        }

        return $order->id;
    }

    private function handleItem(CartItem $item, int $orderId, int $studentId)
    {
        return match ($item->type()) {

            CartItem::LESSON => $this->handleLesson($item->id(), $orderId, $studentId),

            CartItem::EXERCISE => $this->handleExercise($item->id(), $orderId, $studentId),

            CartItem::REQUESTED_LESSON => $this->handleRequest($item->id(), $orderId, $studentId),

            CartItem::COURSE_LESSONS => $this->handleLessonsOfCourse($item->id(), $orderId, $studentId),

            CartItem::COURSE_EXERCISES => $this->handleExercisesOfCourse($item->id(), $orderId, $studentId),

            CartItem::FULL_COURSE => $this->handleFullCourse($item->id(), $orderId, $studentId),

            default => null,
        };
    }

    private function handleLessonsOfCourse($courseId, $orderId, $studentId)
    {
        $lessons = Lesson::where('course_id', $courseId)->get();
        foreach ($lessons as $l) {
            $this->handleLesson($l->id, $orderId, $studentId);
        }
    }

    private function handleExercisesOfCourse($courseId, $orderId, $studentId)
    {
        $exs = Exercise::where('course_id', $courseId)->get();
        foreach ($exs as $e) {
            $this->handleExercise($e->id, $orderId, $studentId);
        }
    }

    private function handleFullCourse($courseId, $orderId, $studentId)
    {
        $this->handleLessonsOfCourse($courseId, $orderId, $studentId);
        $this->handleExercisesOfCourse($courseId, $orderId, $studentId);
    }

    private function handleLesson(int $id, int  $orderId, int $studentId)
    {
        $lesson = Lesson::find($id);
        $this->createOrderProduct($orderId, $id, 0, $lesson->price);
        $this->createChat($id, 0, $studentId);
    }

    private function handleExercise(int $id, int $orderId, int $studentId)
    {
        $ex = Exercise::find($id);
        $this->createOrderProduct($orderId, $id, 2, $ex->price);
        $this->createChat($id, 2, $studentId);
    }

    private function handleRequest(int $id, int $orderId, int $studentId)
    {
        $req = LessonOnRequest::find($id);
        $req->update(['paid' => 1]);
        $this->createOrderProduct($orderId, $id, 5, $req->price);
        $this->createChat($id, 5, $studentId);
    }

    private function createOrderProduct(int $orderId, int $productId, int  $type, int  $price)
    {
        OrderProduct::create([
            'id_ordine' => $orderId,
            'id_prodotto' => $productId,
            'tipo_prodotto' => $type,
            'price' => $price,
            'description' => match ($type) {
                0 => 'Lesson: ' . Lesson::find($productId)->title,
                2 => 'Exercise: ' . Exercise::find($productId)->title,
                5 => 'Request: ' . LessonOnRequest::find($productId)->title,
                default => 'Unknown product',
            }
        ]);
    }

    private function createChat(int $productId, int $type, int $studentId)
    {
        Chat::create([
            'id_prodotto' => $productId,
            'tipo_prodotto' => $type,
            'id_studente' => $studentId
        ]);
    }
}
