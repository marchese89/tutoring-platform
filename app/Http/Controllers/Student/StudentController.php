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

    public function showLesson($course, $lesson)
    {
        $corso = Course::find($course);
        $lezione = Lesson::find($lesson);

        if (!$corso || !$lezione) {
            abort(404);
        }

        $studente = auth()->user()->student;

        $chat = Chat::where('product_id', $lesson)
            ->where('product_type', 0)
            ->where('student_id', $studente->id)
            ->first();

        // Se la chat non esiste la creo
        if (!$chat) {

            $chat = Chat::create([
                'product_id' => $lesson,
                'product_type' => 0,
                'student_id' => $studente->id
            ]);
        }

        $messaggi = $this->chatMessages($chat->id);
        $enableEcho = true;

        return view('student.lesson', compact(
            'corso',
            'lezione',
            'chat',
            'messaggi',
            'enableEcho'
        ));
    }

    public function showExercise(Request $request, $course, $exercise)
    {
        $corso = Course::find($course);
        $esercizio = Exercise::find($exercise);

        // Controlli base
        if (!$corso || !$esercizio) {
            abort(404);
        }

        // 🔴 MIGLIORIA rispetto a prima
        // Verifica che l'esercizio appartenga al corso
        if ($esercizio->course_id != $corso->id) {
            abort(404);
        }

        $chat = Chat::firstOrCreate([
            'product_id' => $exercise,
            'product_type' => 2,
            'student_id' => $request->user()->student->id,
        ]);

        $messaggi = $this->chatMessages($chat->id);
        $enableEcho = true;

        return view('student.exercise', compact('corso', 'esercizio', 'chat', 'messaggi', 'enableEcho'));
    }

    public function showDirectRequest(Request $request, int $id)
    {
        $richiesta = LessonRequest::where('student_id', $request->user()->student->id)->findOrFail($id);

        $chat = Chat::where('product_id', $id)
            ->where('product_type', 5)
            ->where('student_id', $request->user()->student->id)
            ->first();

        if (!$chat && (int) $richiesta->is_paid === 1) {
            $chat = Chat::create([
                'product_id' => $id,
                'product_type' => 5,
                'student_id' => $request->user()->student->id,
            ]);
        }

        $messaggi = $chat ? $this->chatMessages($chat->id) : collect();
        $enableEcho = (bool) $chat;

        return view('student.direct-request', compact('richiesta', 'chat', 'messaggi', 'enableEcho'));
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
