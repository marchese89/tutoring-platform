<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Chat;
use App\Models\ChatMessage;

class StudentController extends Controller
{
    public function updateAddress(Request $request)
    {
        $indirizzo = $request->input('inputIndirizzo');
        $numeroCivico =  $request->input('inputNumeroCivico');
        $citta =  $request->input('inputCitta');
        $provincia =  $request->input('inputProvincia');
        $cap =    $request->input('inputCAP');

        $student = auth()->user()->student;

        $student->street = $indirizzo;
        $student->house_number = $numeroCivico;
        $student->city = $citta;
        $student->province = $provincia;
        $student->postal_code = $cap;

        $student->save();

        return redirect()->route('student.account.profile');
    }

    function updateEmail(Request $request)
    {
        $email = $request->input('inputEmail');
        $user = DB::table('users')->where('email', '=', $email)->first();
        if ($user != null) {
            return back()->withErrors([
                'email' => 'Email già presente',
            ])->onlyInput('email');
        } else {
            $usr = User::where('email', '=', auth()->user()->email)->first();
            $usr->email = $email;
            $usr->save();
            return redirect()->route('student.account.credentials');
        }
    }

    function updatePassword(Request $request)
    {

        $pass_old = password_hash($request->input('inputPassword_old'), PASSWORD_DEFAULT);
        if (Hash::check($pass_old, auth()->user()->password)) {
            return back()->withErrors([
                'pass0' => 'La password non corrisponde  a quella già inserita'
            ]);
        }

        $new_pass = $request->input('inputPassword');
        $confirm_pass = $request->input('inputPassword2');

        $usr = User::where('email', '=', auth()->user()->email)->first();

        $usr->password = password_hash($new_pass, PASSWORD_DEFAULT);

        $usr->save();

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

        $messaggi = ChatMessage::where('chat_id', $chat->id)
            ->orderBy('date', 'asc')
            ->get();

        return view('student.lesson', compact(
            'corso',
            'lezione',
            'chat',
            'messaggi'
        ));
    }

    public function showExercise($id_corso, $id_esercizio)
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

        return view('student.exercise', compact('corso', 'esercizio'));
    }

    public function showDirectRequest(int $id)
    {
        return view('student.direct-request', compact('id'));
    }
}
