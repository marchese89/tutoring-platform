<?php

namespace App\Http\Controllers\Student;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function updateAddress(Request $request)
    {
        $validated = $request->validate(
            [
                'street' => ['required', 'string', 'max:255'],
                'house_number' => ['required', 'string', 'max:6'],
                'city' => ['required', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:2'],
                'postal_code' => ['required', 'string', 'max:5'],
            ],
            [],
            [
                'street' => 'indirizzo',
                'house_number' => 'numero civico',
                'city' => 'città',
                'province' => 'provincia',
                'postal_code' => 'CAP',
            ]
        );

        $student = $request->user()->student;

        $student->update($validated);

        return redirect()->route('student.account.profile');
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->user()->id),
            ],
        ]);

        $user = $request->user();
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('student.account.credentials');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate(
            [
                'current_password' => ['required'],
                'password' => [
                    'required',
                    'min:10',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@#!?.:,;]/',
                ],
                'password_confirmation' => ['required', 'same:password'],
            ],
            [],
            [
                'current_password' => 'password attuale',
                'password' => 'nuova password',
                'password_confirmation' => 'conferma password',
            ]
        );

        if (! Hash::check($validated['current_password'], $request->user()->password)) {
            return back()->withErrors([
                'current_password' => 'La password non corrisponde a quella gia inserita',
            ]);
        }

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('student.account.credentials')->withSuccess('Password Modificata con successo');
    }

    public function showLesson(Request $request, int $courseId, int $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);

        abort_unless($lesson->course_id === $course->id, 404);

        $this->authorize('view', $lesson);

        $student = $request->user()->student;

        $chat = Chat::where('product_id', $lessonId)
            ->where('product_type', 0)
            ->where('student_id', $student->id)
            ->first();

        // Create the support chat on first access.
        if (! $chat) {

            $chat = Chat::create([
                'product_id' => $lessonId,
                'product_type' => 0,
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
            'product_type' => 2,
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
            ->where('product_type', 5)
            ->where('student_id', $request->user()->student->id)
            ->first();

        if (! $chat && (int) $lessonRequest->is_paid === 1) {
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
            ->map(fn (ChatMessage $message) => [
                'message' => $message->message,
                'sender_role' => $message->sender_role,
                'is_teacher' => (int) $message->sender_role === 1,
                'sender' => (int) $message->sender_role === 1 ? 'Insegnante' : 'Tu',
                'date' => DateHelper::format($message->sent_at),
            ]);
    }
}
