<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use App\Models\ChatMessage;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Events\MessageSent;

class AjaxController extends Controller
{

    public function getOrdini(Request $request)
    {
        $anno = $request->input('anno');
        $mese = $request->input('mese');

        $query = Order::query()
            ->with('student.user')
            ->leftJoin('order_products', 'orders.id', '=', 'order_products.id_ordine')
            ->select(
                'orders.id',
                'orders.date',
                'orders.student_id',
                DB::raw('SUM(order_products.price) as totale')
            )
            ->whereMonth('orders.date', $mese)
            ->whereYear('orders.date', $anno)
            ->groupBy('orders.id', 'orders.date', 'orders.student_id');

        // filtro studente
        if (auth()->user()->student !== null) {
            $query->where('orders.student_id', auth()->user()->student->id);
        }

        $ordini = $query
            ->orderByDesc('orders.date')
            ->get();

        // mapping leggero (niente più calcoli)
        $ordiniMapped = $ordini->map(function ($order) {
            return [
                'id' => $order->id,
                'data' => DateHelper::format($order->date),
                'totale' => $order->totale ?? 0,
                'studente' => $order->student->user->name . ' ' . $order->student->user->surname,
            ];
        });

        // return response()->json([
        //     'ordini' => $ordiniMapped,
        //     'totale' => $ordiniMapped->sum('totale'),
        // ]);
        return response()->json([
            'html' => view('admin.partials.righe-ordini', [
                'ordini' => $ordiniMapped
            ])->render(),
            'totale' => $ordiniMapped->sum('totale')
        ]);
    }

    public function invia_messaggio(Request $request)
    {
        $request->validate([
            'id_chat' => 'required|integer|exists:chats,id',
            'testo' => 'required|string'
        ]);

        $messaggio = ChatMessage::create([
            'chat_id' => $request->id_chat,
            'message' => $request->testo,
            'author' => auth()->user()->role === 'admin' ? 1 : 0
        ]);

        broadcast(new MessageSent($messaggio));

        return response()->json([
            'success' => true,
            'message' => $messaggio
        ]);
    }

    public function invia_feedback()
    {
        $punteggio = request('punteggio');
        //cerchiamo per vedere se c'è già un punteggio assegnato
        $id_studente = auth()->user()->student->id;
        $feedback = Feedback::where('student_id', '=', $id_studente)->first();
        if ($feedback != null) {
            $feedback->punteggio = $punteggio;
        } else {
            $feedback = new Feedback();
            $feedback->student_id = $id_studente;
            $feedback->punteggio = $punteggio;
        }

        $feedback->save();

        $response = '<a ';
        if ($punteggio > 0) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response . ' onclick="invia_feefback(1)">⭐</a>
    <a ';
        if ($punteggio > 1) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response .  ' onclick="invia_feefback(2)">⭐</a>
    <a ';
        if ($punteggio > 2) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response . ' onclick="invia_feefback(3)">⭐</a>
    <a ';
        if ($punteggio > 3) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response . ' onclick="invia_feefback(4)">⭐</a>
    <a ';
        if ($punteggio > 4) {
            $response = $response . 'style="opacity: 100%;"';
        }
        $response = $response .   ' onclick="invia_feefback(5)">⭐</a>';

        return $response;
    }

    public function invia_recensione()
    {
        $testo = request('testo');
        $id_studente = auth()->user()->student->id;
        $feedback = Feedback::where('student_id', '=', $id_studente)->first();
        if ($feedback != null) {
            $feedback->recensione = $testo;
        } else {
            $feedback = new Feedback();
            $feedback->student_id = $id_studente;
            $feedback->recensione = $testo;
        }

        $feedback->save();
        return $testo;
    }
}
