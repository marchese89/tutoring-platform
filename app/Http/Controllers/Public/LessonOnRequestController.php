<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\LessonOnRequest;
use App\Models\User;
use App\Mail\NuovaRichiestaStudenteMail;
use App\Mail\RichiestaEvasaMail;
use Illuminate\Support\Facades\Storage;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Exercise;

class LessonOnRequestController extends Controller
{
    public function index()
    {
        $lezioni_su_richiesta = LessonOnRequest::with('student.user')->get();
        return view('admin.students.richieste-studenti', compact('lezioni_su_richiesta'));
    }

    public function visualizzaRichiesta(int $id)
    {
        $richiesta = LessonOnRequest::where('id', '=', $id)->first();
        return view('admin.students.visualizza-richiesta-lezione', compact('richiesta'));
    }

    public function chatStudenti()
    {
        $chat = Chat::orderBy('created_at', 'desc')->get();

        foreach ($chat as $item) {

            // TIPO PRODOTTO
            switch ($item->tipo_prodotto) {

                case 0:
                    $item->tipo_stringa = 'Lezione';

                    $lezione = Lesson::find($item->id_prodotto);
                    $item->nome_prodotto = $lezione?->title;
                    break;

                case 2:
                    $item->tipo_stringa = 'Esercizio';

                    $esercizio = Exercise::find($item->id_prodotto);
                    $item->nome_prodotto = $esercizio?->title;
                    break;

                case 5:
                    $item->tipo_stringa = 'Lezione su Richiesta';

                    $lezioneRich = LessonOnRequest::find($item->id_prodotto);
                    $item->nome_prodotto = $lezioneRich?->title;
                    break;

                default:
                    $item->tipo_stringa = '-';
                    $item->nome_prodotto = '-';
                    break;
            }

            // STUDENTE
            $studente = Student::find($item->id_studente);

            if ($studente && $studente->user) {
                $item->studente_nome =
                    $studente->user->name . ' ' . $studente->user->surname;
            } else {
                $item->studente_nome = '-';
            }

            // STATO CHAT
            $ultimoMessaggio = ChatMessage::where('chat_id', $item->id)
                ->orderBy('date', 'desc')
                ->first();

            $item->non_letta_admin =
                $ultimoMessaggio && $ultimoMessaggio->author == 0;
        }

        return view('admin.students.chat-studenti', compact('chat'));
    }

    public function visualizzaChat($id)
    {
        $chat = Chat::findOrFail($id);

        $pres = '';
        $exec = '';
        $titolo = '';

        switch ($chat->tipo_prodotto) {

            case 0:

                $lezione = Lesson::find($chat->id_prodotto);

                $pres = $lezione?->presentation;
                $exec = $lezione?->lesson;

                $titolo =
                    'Lezione n. ' .
                    $lezione?->id .
                    ', ' .
                    $lezione?->title;

                break;

            case 2:

                $esercizio = Exercise::find($chat->id_prodotto);

                $pres = $esercizio?->trace;
                $exec = $esercizio?->execution;

                $titolo =
                    'Esercizio n. ' .
                    $esercizio?->id .
                    ', ' .
                    $esercizio?->title;

                break;

            case 5:

                $lezioneRich = LessonOnRequest::find($chat->id_prodotto);

                $pres = $lezioneRich?->trace;
                $exec = $lezioneRich?->execution;

                $titolo =
                    'Lezione su richiesta n. ' .
                    $lezioneRich?->id .
                    ', ' .
                    $lezioneRich?->title;

                break;
        }

        $messaggi = ChatMessage::where('chat_id', $chat->id)
            ->orderBy('date', 'asc')
            ->get();

        $studente = Student::find($chat->id_studente);

        $utente = $studente?->user;

        return view('admin.students.visualizza-chat', compact(
            'chat',
            'pres',
            'exec',
            'titolo',
            'messaggi',
            'utente'
        ));
    }

    private function deleteFile($path = null)
    {
        !empty($path) && Storage::disk('private')->delete($path);
    }

    private function saveFile($file, $path)
    {
        $name = $file->store($path, 'private');
        return $name;
    }

    public function add_file_su_richiesta(Request $request)
    {
        $this->deleteFile($request->session()->get('uploaded_lez_rich'));

        $file = $request->file('file');
        $name = $this->saveFile($file, 'lessons_on_request/trace');

        $request->session()->put('uploaded_lez_rich', $name);

        return redirect()->route('lesson-requests.create');
    }

    public function elimina_lez_rich(Request $request)
    {
        $this->deleteFile($request->session()->get('uploaded_lez_rich'));
        $request->session()->forget('uploaded_lez_rich');

        return redirect()->route('lesson-requests.create');
    }

    public function carica_lez_rich(Request $request)
    {
        LessonOnRequest::create([
            'title' => $request->input('titolo'),
            'student_id' => $request->user()->student->id,
            'trace' => $request->session()->get('uploaded_lez_rich'),
        ]);

        $request->session()->forget('uploaded_lez_rich');

        $admin = User::where('role', 'admin')->first();

        Mail::to($admin->email)
            ->send(new NuovaRichiestaStudenteMail());

        return redirect()->route('lesson-requests.success');
    }

    public function sol_rich_upload(Request $request, int $id)
    {
        $lezione = LessonOnRequest::findOrFail($id);

        $this->deleteFile($lezione->execution);

        $path = $this->saveFile($request->file('file'), 'lessons_on_request/execution');

        $lezione->update([
            'execution' => $path
        ]);

        return redirect()->route('admin.lesson-requests.show', $lezione->id);
    }

    public function lez_rich_rem_exec(Request $request, int $id)
    {
        $lezione = LessonOnRequest::findOrFail($id);

        $this->deleteFile($lezione->execution);

        $lezione->update([
            'execution' => null
        ]);

        return redirect()->route('admin.lesson-requests.show', $lezione->id);
    }

    public function carica_prezzo_lez_rich(Request $request, int $id)
    {
        $lezione = LessonOnRequest::findOrFail($id);

        $lezione->update([
            'price' => $request->prezzo,
            'escaped' => 1
        ]);

        $user = $lezione->student->user;

        Mail::to($user->email)
            ->send(new RichiestaEvasaMail());

        return redirect()->route('admin.lesson-requests.show', $lezione->id);
    }
}
