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
use App\Models\LessonOnRequest;

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

    public function showLesson($id_corso, $id_lezione)
    {
        $corso = Course::find($id_corso);
        $lezione = Lesson::find($id_lezione);

        if (!$corso || !$lezione) {
            abort(404);
        }

        $studente = auth()->user()->student;

        $chat = Chat::where('id_prodotto', $id_lezione)
            ->where('tipo_prodotto', 0)
            ->where('id_studente', $studente->id)
            ->first();

        // Se la chat non esiste la creo
        if (!$chat) {

            $chat = Chat::create([
                'id_prodotto' => $id_lezione,
                'tipo_prodotto' => 0,
                'id_studente' => $studente->id
            ]);
        }

        $messaggi = $this->chatMessages($chat->id);

        return view('student.lesson', compact(
            'corso',
            'lezione',
            'chat',
            'messaggi'
        ));
    }

    public function showExercise(Request $request, $id_corso, $id_esercizio)
    {
        $corso = Course::find($id_corso);
        $esercizio = Exercise::find($id_esercizio);

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
            'id_prodotto' => $id_esercizio,
            'tipo_prodotto' => 2,
            'id_studente' => $request->user()->student->id,
        ]);

        $messaggi = $this->chatMessages($chat->id);
        $enableEcho = true;

        return view('student.exercise', compact('corso', 'esercizio', 'chat', 'messaggi', 'enableEcho'));
    }

    public function showDirectRequest(Request $request, int $id)
    {
        $richiesta = LessonOnRequest::where('student_id', $request->user()->student->id)->findOrFail($id);

        $chat = Chat::where('id_prodotto', $id)
            ->where('tipo_prodotto', 5)
            ->where('id_studente', $request->user()->student->id)
            ->first();

        if (!$chat && (int) $richiesta->paid === 1) {
            $chat = Chat::create([
                'id_prodotto' => $id,
                'tipo_prodotto' => 5,
                'id_studente' => $request->user()->student->id,
            ]);
        }

        $messaggi = $chat ? $this->chatMessages($chat->id) : collect();
        $enableEcho = (bool) $chat;

        return view('student.direct-request', compact('richiesta', 'chat', 'messaggi', 'enableEcho'));
    }

    private function chatMessages(int $chatId)
    {
        return ChatMessage::where('chat_id', $chatId)
            ->orderBy('date', 'asc')
            ->get()
            ->map(fn(ChatMessage $message) => [
                'message' => $message->message,
                'author' => $message->author,
                'is_teacher' => (int) $message->author === 1,
                'sender' => (int) $message->author === 1 ? 'Insegnante' : 'Tu',
                'date' => DateHelper::format($message->date),
            ]);
    }
}
