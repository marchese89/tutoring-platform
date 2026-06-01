<?php

namespace App\Http\Controllers\Student;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\LessonOnRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectRequestController extends Controller
{
    public function index(Request $request): View
    {
        $directRequests = LessonOnRequest::where('student_id', $request->user()->student->id)
            ->where('paid', 0)
            ->orderByDesc('date')
            ->get();

        return view('student.direct-requests', compact('directRequests'));
    }

    public function purchased(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $lessons = LessonOnRequest::where('student_id', $studentId)
            ->where('paid', 1)
            ->orderByDesc('date')
            ->get();

        $chats = Chat::where('tipo_prodotto', 5)
            ->where('id_studente', $studentId)
            ->whereIn('id_prodotto', $lessons->pluck('id'))
            ->get()
            ->keyBy('id_prodotto');

        $latestMessages = ChatMessage::whereIn('chat_id', $chats->pluck('id'))
            ->orderByDesc('date')
            ->get()
            ->unique('chat_id')
            ->keyBy('chat_id');

        $purchasedDirectRequests = $lessons->map(function (LessonOnRequest $lesson) use ($chats, $latestMessages) {
            $chat = $chats->get($lesson->id);
            $latestMessage = $chat ? $latestMessages->get($chat->id) : null;

            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'date' => DateHelper::format($lesson->date),
                'has_unread_message' => $latestMessage?->author === 1,
            ];
        });

        return view('student.purchased-direct-requests', compact('purchasedDirectRequests'));
    }
}
