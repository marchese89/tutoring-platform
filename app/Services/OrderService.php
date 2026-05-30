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
    public function process(Student $studente, Cart $carrello): int
    {

        $order = Order::create([
            'student_id' => $studente->id,
        ]);

        $items = $carrello->contenuto();

        foreach ($items as $item) {
            $this->handleItem($item, $order->id, $studente->id);
        }

        return $order->id;
    }

    private function handleItem(CartItem $item, int $orderId, int $studentId)
    {
        return match ($item->getTipo()) {

            CartItem::LEZIONE => $this->handleLesson($item->getId(), $orderId, $studentId),

            CartItem::ESERCIZIO => $this->handleExercise($item->getId(), $orderId, $studentId),

            CartItem::LEZIONE_RICHIESTA => $this->handleRequest($item->getId(), $orderId, $studentId),

            CartItem::LEZIONI_CORSO => $this->handleLessonsOfCourse($item->getId(), $orderId, $studentId),

            CartItem::ESERCIZI_CORSO => $this->handleExercisesOfCourse($item->getId(), $orderId, $studentId),

            CartItem::CORSO_COMPLETO => $this->handleFullCourse($item->getId(), $orderId, $studentId),

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
                0 => 'Lezione: ' . Lesson::find($productId)->title,
                2 => 'Esercizio: ' . Exercise::find($productId)->title,
                5 => 'Richiesta: ' . LessonOnRequest::find($productId)->title,
                default => 'Prodotto sconosciuto',
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
