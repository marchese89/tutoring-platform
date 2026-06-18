<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ChatSenderRole;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Student;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with(['student.user', 'latestMessage'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $chatItems = $chats->getCollection();

        $lessonTitles = Lesson::whereIn(
            'id',
            $chatItems->where('product_type', ProductType::LESSON->value)->pluck('product_id')
        )->pluck('title', 'id');

        $exerciseTitles = Exercise::whereIn(
            'id',
            $chatItems->where('product_type', ProductType::EXERCISE->value)->pluck('product_id')
        )->pluck('title', 'id');

        $lessonRequestTitles = LessonRequest::whereIn(
            'id',
            $chatItems->where('product_type', ProductType::REQUESTED_LESSON->value)->pluck('product_id')
        )->pluck('title', 'id');

        $chatItems->each(function (Chat $chat) use ($lessonTitles, $exerciseTitles, $lessonRequestTitles) {
            $chat->product_type_label = match ((int) $chat->product_type) {
                ProductType::LESSON->value => __('admin.students.lesson_type'),
                ProductType::EXERCISE->value => __('admin.students.exercise_type'),
                ProductType::REQUESTED_LESSON->value => __('admin.students.requested_lesson_type'),
                default => '-',
            };

            $chat->product_name = match ((int) $chat->product_type) {
                ProductType::LESSON->value => $lessonTitles->get($chat->product_id, '-'),
                ProductType::EXERCISE->value => $exerciseTitles->get($chat->product_id, '-'),
                ProductType::REQUESTED_LESSON->value => $lessonRequestTitles->get($chat->product_id, '-'),
                default => '-',
            };

            $chat->student_name = $chat->student?->user
                ? trim($chat->student->user->name.' '.$chat->student->user->surname)
                : '-';

            $chat->has_unread_admin_message =
                $chat->latestMessage && (int) $chat->latestMessage->sender_role === ChatSenderRole::STUDENT->value;
        });

        return view('admin.students.chats', compact('chats'));
    }

    public function show(Chat $chat)
    {
        $presentationFile = '';
        $contentFile = '';
        $presentationLabel = __('admin.students.presentation');
        $contentLabel = __('admin.students.content');
        $title = '';

        switch ($chat->product_type) {
            case ProductType::LESSON->value:
                $lesson = Lesson::find($chat->product_id);
                $presentationFile = $lesson?->presentation_file;
                $contentFile = $lesson?->content_file;
                $presentationLabel = __('admin.students.presentation');
                $contentLabel = __('admin.students.content');
                $title = __('admin.students.lesson_chat_title', [
                    'number' => $lesson?->id,
                    'title' => $lesson?->title,
                ]);
                break;

            case ProductType::EXERCISE->value:
                $exercise = Exercise::find($chat->product_id);
                $presentationFile = $exercise?->prompt_file;
                $contentFile = $exercise?->solution_file;
                $presentationLabel = __('admin.students.prompt');
                $contentLabel = __('admin.students.solution');
                $title = __('admin.students.exercise_chat_title', [
                    'number' => $exercise?->id,
                    'title' => $exercise?->title,
                ]);
                break;

            case ProductType::REQUESTED_LESSON->value:
                $lessonRequest = LessonRequest::find($chat->product_id);
                $presentationFile = $lessonRequest?->request_file;
                $contentFile = $lessonRequest?->solution_file;
                $presentationLabel = __('admin.students.student_request');
                $contentLabel = __('admin.students.solution');
                $title = __('admin.students.requested_lesson_chat_title', [
                    'number' => $lessonRequest?->id,
                    'title' => $lessonRequest?->title,
                ]);
                break;
        }

        $messages = ChatMessage::where('chat_id', $chat->id)
            ->orderBy('sent_at', 'asc')
            ->get();

        $student = Student::find($chat->student_id);
        $user = $student?->user;
        $studentName = trim(($user?->name ?? '').' '.($user?->surname ?? '')) ?: __('admin.students.fallback_student');
        $enableEcho = true;

        return view('admin.students.chat', compact(
            'chat',
            'presentationFile',
            'contentFile',
            'presentationLabel',
            'contentLabel',
            'title',
            'messages',
            'studentName',
            'enableEcho'
        ));
    }
}
