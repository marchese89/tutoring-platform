<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use App\Models\ChatMessage;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Events\MessageSent;

class AjaxController extends Controller
{

    public function getOrders(Request $request)
    {
        $anno = $request->input('anno');
        $mese = $request->input('mese');

        $query = Order::query()
            ->with('student.user')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'orders.id',
                'orders.ordered_at',
                'orders.student_id',
                DB::raw('SUM(order_items.price) as totale')
            )
            ->whereMonth('orders.ordered_at', $mese)
            ->whereYear('orders.ordered_at', $anno)
            ->groupBy('orders.id', 'orders.ordered_at', 'orders.student_id');

        // filtro studente
        if (auth()->user()->student !== null) {
            $query->where('orders.student_id', auth()->user()->student->id);
        }

        $ordini = $query
            ->orderByDesc('orders.ordered_at')
            ->get();

        // mapping leggero (niente più calcoli)
        $ordiniMapped = $ordini->map(function ($order) {
            return [
                'id' => $order->id,
                'data' => DateHelper::format($order->ordered_at),
                'totale' => $order->totale ?? 0,
                'studente' => $order->student->user->name . ' ' . $order->student->user->surname,
            ];
        });

        // return response()->json([
        //     'ordini' => $ordiniMapped,
        //     'totale' => $ordiniMapped->sum('totale'),
        // ]);
        return response()->json([
            'html' => view('admin.partials.order-rows', [
                'ordini' => $ordiniMapped
            ])->render(),
            'totale' => $ordiniMapped->sum('totale')
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'id_chat' => 'required|integer|exists:chats,id',
            'testo' => 'required|string'
        ]);

        $messaggio = ChatMessage::create([
            'chat_id' => $request->id_chat,
            'message' => $request->testo,
            'sender_role' => auth()->user()->role === 'admin' ? 1 : 0,
            'sent_at' => now(),
        ]);

        broadcast(new MessageSent($messaggio));

        return response()->json([
            'success' => true,
            'message' => $messaggio
        ]);
    }

    public function storeFeedback()
    {
        $rating = request('rating');
        //cerchiamo per vedere se c'è già un rating assegnato
        $student_id = auth()->user()->student->id;
        $feedback = Review::where('student_id', '=', $student_id)->first();
        if ($feedback != null) {
            $feedback->rating = $rating;
        } else {
            $feedback = new Review();
            $feedback->student_id = $student_id;
            $feedback->rating = $rating;
        }

        $feedback->save();

        $response = '<a ';
        if ($rating > 0) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response . ' onclick="invia_feefback(1)">⭐</a>
    <a ';
        if ($rating > 1) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response .  ' onclick="invia_feefback(2)">⭐</a>
    <a ';
        if ($rating > 2) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response . ' onclick="invia_feefback(3)">⭐</a>
    <a ';
        if ($rating > 3) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response . ' onclick="invia_feefback(4)">⭐</a>
    <a ';
        if ($rating > 4) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response .   ' onclick="invia_feefback(5)">⭐</a>';

        return $response;
    }

    public function storeReview()
    {
        $testo = request('testo');
        $student_id = auth()->user()->student->id;
        $feedback = Review::where('student_id', '=', $student_id)->first();
        if ($feedback != null) {
            $feedback->review = $testo;
        } else {
            $feedback = new Review();
            $feedback->student_id = $student_id;
            $feedback->review = $testo;
        }

        $feedback->save();
        return $testo;
    }
}
