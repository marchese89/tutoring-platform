<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use App\Models\ChatMessage;
use App\Models\Chat;
use App\Models\Student;
use App\Models\Feedback;
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
            'author' => auth()->user()->role === 'admin' ? 1 : 0
        ]);

        broadcast(new MessageSent($messaggio));

        return response()->json([
            'success' => true,
            'message' => $messaggio
        ]);
    }

    public function getMessages(int $id_chat)
    {
        return $this->renderMessages($id_chat, true);
    }

    public function getStudentMessages(int $id_chat)
    {
        return $this->renderMessages($id_chat, false);
    }

    private function renderMessages(int $chatId, bool $adminPerspective): string
    {
        $chat = Chat::findOrFail($chatId);
        $messages = ChatMessage::where('chat_id', $chatId)
            ->orderBy('date', 'asc')
            ->get();
        $student = Student::with('user')->findOrFail($chat->id_studente);
        $studentName = e($student->user->name . ' ' . $student->user->surname);
        $html = '';

        foreach ($messages as $item) {
            $isAdminAuthor = (int) $item->author === 1;
            $isOwnMessage = $adminPerspective ? $isAdminAuthor : !$isAdminAuthor;
            $sender = $isOwnMessage ? 'Tu' : ($adminPerspective ? $studentName : 'Insegnante');
            $alignment = $isOwnMessage ? 'flex-end' : 'flex-start';
            $contentStyle = $isOwnMessage ? ' style="background-color: #5755c559;"' : '';

            $html .= '<div class="chat-message" style="justify-content: ' . $alignment . ';">
                        <div class="message-content"' . $contentStyle . '>
                            <p class="sender-name">' . $sender . '</p>
                            <p class="message-text">' . e($item->message) . '</p>
                            <span class="timestamp">' . DateHelper::format($item->date) . '</span>
                        </div>
                    </div>';
        }

        return $html;
    }

    public function storeFeedback()
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

    public function storeReview()
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
