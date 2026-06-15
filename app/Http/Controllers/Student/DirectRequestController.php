<?php

namespace App\Http\Controllers\Student;

use App\Enums\ChatSenderRole;
use App\Enums\ProductType;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\LessonRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectRequestController extends Controller
{
    public function index(Request $request): View
    {
        $directRequests = LessonRequest::where('student_id', $request->user()->student->id)
            ->where('is_paid', 0)
            ->orderByDesc('requested_at')
            ->get()
            ->map(fn (LessonRequest $lessonRequest) => [
                'id' => $lessonRequest->id,
                'title' => $lessonRequest->title,
                'date' => DateHelper::format($lessonRequest->requested_at),
                'status_variant' => $lessonRequest->is_fulfilled ? 'success' : 'danger',
                'status_label' => $lessonRequest->is_fulfilled
                    ? __('student.requests.fulfilled')
                    : __('student.requests.pending'),
                'show_url' => route('student.direct-requests.show', $lessonRequest->id),
            ]);

        return view('student.direct-requests', compact('directRequests'));
    }

    public function purchased(Request $request): View
    {
        $studentId = $request->user()->student->id;

        $lessons = LessonRequest::where('student_id', $studentId)
            ->where('is_paid', 1)
            ->orderByDesc('requested_at')
            ->get();

        $chats = Chat::where('product_type', ProductType::REQUESTED_LESSON->value)
            ->where('student_id', $studentId)
            ->whereIn('product_id', $lessons->pluck('id'))
            ->get()
            ->keyBy('product_id');

        $latestMessages = ChatMessage::whereIn('chat_id', $chats->pluck('id'))
            ->orderByDesc('sent_at')
            ->get()
            ->unique('chat_id')
            ->keyBy('chat_id');

        $purchasedDirectRequests = $lessons->map(function (LessonRequest $lesson) use ($chats, $latestMessages) {
            $chat = $chats->get($lesson->id);
            $latestMessage = $chat ? $latestMessages->get($chat->id) : null;

            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'date' => DateHelper::format($lesson->requested_at),
                'status_variant' => (int) $latestMessage?->sender_role === ChatSenderRole::ADMIN->value
                    ? 'danger'
                    : 'success',
                'status_label' => (int) $latestMessage?->sender_role === ChatSenderRole::ADMIN->value
                    ? __('student.requests.unread')
                    : __('student.requests.no_new_messages'),
                'show_url' => route('student.direct-requests.show', $lesson->id),
            ];
        });

        return view('student.purchased-direct-requests', compact('purchasedDirectRequests'));
    }
}
