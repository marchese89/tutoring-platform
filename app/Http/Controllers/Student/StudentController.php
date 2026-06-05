<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Helpers\DateHelper;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\LessonRequest;

class StudentController extends Controller
{
    public function updateAddress(Request $request)
    {
        $validated = $request->validate([
            'inputIndirizzo' => ['required', 'string', 'max:255'],
            'inputNumeroCivico' => ['required', 'string', 'max:6'],
            'inputCitta' => ['required', 'string', 'max:255'],
            'inputProvincia' => ['required', 'string', 'max:2'],
            'inputCAP' => ['required', 'string', 'max:5'],
        ]);

        $student = $request->user()->student;

        $student->street = $validated['inputIndirizzo'];
        $student->house_number = $validated['inputNumeroCivico'];
        $student->city = $validated['inputCitta'];
        $student->province = $validated['inputProvincia'];
        $student->postal_code = $validated['inputCAP'];

        $student->save();

        return redirect()->route('student.account.profile');
    }

    function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'inputEmail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->user()->id),
            ],
        ]);

        $user = $request->user();
        $user->email = $validated['inputEmail'];
        $user->save();

        return redirect()->route('student.account.credentials');
    }

    function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'inputPassword_old' => ['required'],
            'inputPassword' => [
                'required',
                'min:10',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@#!?.:,;]/',
            ],
            'inputPassword2' => ['required', 'same:inputPassword'],
        ]);

        if (!Hash::check($validated['inputPassword_old'], $request->user()->password)) {
            return back()->withErrors([
                'inputPassword_old' => 'La password non corrisponde a quella gia inserita',
            ]);
        }

        $user = $request->user();
        $user->password = Hash::make($validated['inputPassword']);
        $user->save();

        return redirect()->route('student.account.credentials')->withSuccess('Password Modificata con successo');
    }

    public function showLesson($courseId, $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);

        if (!$course || !$lesson) {
            abort(404);
        }

        $student = auth()->user()->student;

        $chat = Chat::where('product_id', $lessonId)
            ->where('product_type', 0)
            ->where('student_id', $student->id)
            ->first();

        // Create the support chat on first access.
        if (!$chat) {

            $chat = Chat::create([
                'product_id' => $lessonId,
                'product_type' => 0,
                'student_id' => $student->id
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

    public function showExercise(Request $request, $courseId, $exerciseId)
    {
        $course = Course::find($courseId);
        $exercise = Exercise::find($exerciseId);

        // Basic integrity checks.
        if (!$course || !$exercise) {
            abort(404);
        }

        // Ensure the exercise belongs to the requested course.
        if ($exercise->course_id != $course->id) {
            abort(404);
        }

        $chat = Chat::firstOrCreate([
            'product_id' => $exerciseId,
            'product_type' => 2,
            'student_id' => $request->user()->student->id,
        ]);

        $messages = $this->chatMessages($chat->id);
        $enableEcho = true;

        return view('student.exercise', compact('course', 'exercise', 'chat', 'messages', 'enableEcho'));
    }

    public function showDirectRequest(Request $request, int $id)
    {
        $lessonRequest = LessonRequest::where('student_id', $request->user()->student->id)->findOrFail($id);

        $chat = Chat::where('product_id', $id)
            ->where('product_type', 5)
            ->where('student_id', $request->user()->student->id)
            ->first();

        if (!$chat && (int) $lessonRequest->is_paid === 1) {
            $chat = Chat::create([
                'product_id' => $id,
                'product_type' => 5,
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
            ->get()
            ->map(fn(ChatMessage $message) => [
                'message' => $message->message,
                'sender_role' => $message->sender_role,
                'is_teacher' => (int) $message->sender_role === 1,
                'sender' => (int) $message->sender_role === 1 ? 'Insegnante' : 'Tu',
                'date' => DateHelper::format($message->sent_at),
            ]);
    }
}
