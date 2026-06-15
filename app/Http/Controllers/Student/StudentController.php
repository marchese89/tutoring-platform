<?php

namespace App\Http\Controllers\Student;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function showLesson(Request $request, int $courseId, int $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);

        abort_unless($lesson->course_id === $course->id, 404);

        $this->authorize('view', $lesson);

        $student = $request->user()->student;

        $chat = Chat::where('product_id', $lessonId)
            ->where('product_type', ProductType::LESSON->value)
            ->where('student_id', $student->id)
            ->first();

        // Create the support chat on first access.
        if (! $chat) {

            $chat = Chat::create([
                'product_id' => $lessonId,
                'product_type' => ProductType::LESSON->value,
                'student_id' => $student->id,
            ]);
        }

        $messages = $this->chatMessages($chat->id);
        $enableEcho = true;

        return view('student.lesson', compact(
            'course',
            'lesson',
            'chat',
            'messages',
            'enableEcho'
        ));
    }

    public function showExercise(Request $request, int $courseId, int $exerciseId)
    {
        $course = Course::findOrFail($courseId);
        $exercise = Exercise::findOrFail($exerciseId);

        abort_unless($exercise->course_id === $course->id, 404);

        $this->authorize('view', $exercise);

        $chat = Chat::firstOrCreate([
            'product_id' => $exerciseId,
            'product_type' => ProductType::EXERCISE->value,
            'student_id' => $request->user()->student->id,
        ]);

        $messages = $this->chatMessages($chat->id);
        $enableEcho = true;

        return view('student.exercise', compact('course', 'exercise', 'chat', 'messages', 'enableEcho'));
    }

    public function showDirectRequest(Request $request, int $id)
    {
        $lessonRequest = LessonRequest::findOrFail($id);

        $this->authorize('view', $lessonRequest);

        $chat = Chat::where('product_id', $id)
            ->where('product_type', ProductType::REQUESTED_LESSON->value)
            ->where('student_id', $request->user()->student->id)
            ->first();

        if (! $chat && (int) $lessonRequest->is_paid === 1) {
            $chat = Chat::create([
                'product_id' => $id,
                'product_type' => ProductType::REQUESTED_LESSON->value,
                'student_id' => $request->user()->student->id,
            ]);
        }

        $messages = $chat ? $this->chatMessages($chat->id) : collect();
        $enableEcho = (bool) $chat;

        return view('student.direct-request', compact('lessonRequest', 'chat', 'messages', 'enableEcho'));
    }

    private function chatMessages(int $chatId)
    {
        return ChatMessage::where('chat_id', $chatId)
            ->orderBy('sent_at', 'asc')
            ->get();
    }
}
