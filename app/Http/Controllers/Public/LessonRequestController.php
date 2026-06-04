<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\LessonRequest;
use App\Models\User;
use App\Mail\NewStudentRequestMail;
use App\Mail\LessonRequestFulfilledMail;
use Illuminate\Support\Facades\Storage;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Exercise;

class LessonRequestController extends Controller
{
    public function index()
    {
        $lezioni_su_richiesta = LessonRequest::with('student.user')->get();
        return view('admin.students.lesson-requests', compact('lezioni_su_richiesta'));
    }

    public function show(int $id)
    {
        $richiesta = LessonRequest::where('id', '=', $id)->first();
        return view('admin.students.lesson-request', compact('richiesta'));
    }

    public function studentChats()
    {
        $chat = Chat::orderBy('created_at', 'desc')->get();

        foreach ($chat as $item) {

            // TIPO PRODOTTO
            switch ($item->product_type) {

                case 0:
                    $item->tipo_stringa = 'Lezione';

                    $lezione = Lesson::find($item->product_id);
                    $item->nome_prodotto = $lezione?->title;
                    break;

                case 2:
                    $item->tipo_stringa = 'Esercizio';

                    $esercizio = Exercise::find($item->product_id);
                    $item->nome_prodotto = $esercizio?->title;
                    break;

                case 5:
                    $item->tipo_stringa = 'Lezione su Richiesta';

                    $lezioneRich = LessonRequest::find($item->product_id);
                    $item->nome_prodotto = $lezioneRich?->title;
                    break;

                default:
                    $item->tipo_stringa = '-';
                    $item->nome_prodotto = '-';
                    break;
            }

            // STUDENTE
            $studente = Student::find($item->student_id);

            if ($studente && $studente->user) {
                $item->studente_nome =
                    $studente->user->name . ' ' . $studente->user->surname;
            } else {
                $item->studente_nome = '-';
            }

            // STATO CHAT
            $ultimoMessaggio = ChatMessage::where('chat_id', $item->id)
                ->orderBy('sent_at', 'desc')
                ->first();

            $item->non_letta_admin =
                $ultimoMessaggio && $ultimoMessaggio->sender_role == 0;
        }

        return view('admin.students.chats', compact('chat'));
    }

    public function showChat($id)
    {
        $chat = Chat::findOrFail($id);

        $pres = '';
        $exec = '';
        $titolo = '';

        switch ($chat->product_type) {

            case 0:

                $lezione = Lesson::find($chat->product_id);

                $pres = $lezione?->presentation_file;
                $exec = $lezione?->content_file;

                $titolo =
                    'Lezione n. ' .
                    $lezione?->id .
                    ', ' .
                    $lezione?->title;

                break;

            case 2:

                $esercizio = Exercise::find($chat->product_id);

                $pres = $esercizio?->prompt_file;
                $exec = $esercizio?->solution_file;

                $titolo =
                    'Esercizio n. ' .
                    $esercizio?->id .
                    ', ' .
                    $esercizio?->title;

                break;

            case 5:

                $lezioneRich = LessonRequest::find($chat->product_id);

                $pres = $lezioneRich?->request_file;
                $exec = $lezioneRich?->solution_file;

                $titolo =
                    'Lezione su richiesta n. ' .
                    $lezioneRich?->id .
                    ', ' .
                    $lezioneRich?->title;

                break;
        }

        $messaggi = ChatMessage::where('chat_id', $chat->id)
            ->orderBy('sent_at', 'asc')
            ->get();

        $studente = Student::find($chat->student_id);

        $utente = $studente?->user;

        return view('admin.students.chat', compact(
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

    public function storeRequestFile(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $this->deleteFile($request->session()->get('uploaded_lez_rich'));

        $file = $request->file('file');
        $name = $this->saveFile($file, 'lesson_requests/request_files');

        $request->session()->put('uploaded_lez_rich', $name);

        return redirect()->route('lesson-requests.create');
    }

    public function destroyRequestFile(Request $request)
    {
        $this->deleteFile($request->session()->get('uploaded_lez_rich'));
        $request->session()->forget('uploaded_lez_rich');

        return redirect()->route('lesson-requests.create');
    }

    public function store(Request $request)
    {
        if (!$request->session()->has('uploaded_lez_rich')) {
            return redirect()
                ->route('lesson-requests.create')
                ->withErrors(['file' => 'Carica un file prima di inviare la richiesta.']);
        }

        $request->validate([
            'titolo' => ['required', 'string', 'max:255'],
        ]);

        LessonRequest::create([
            'title' => $request->input('titolo'),
            'student_id' => $request->user()->student->id,
            'request_file' => $request->session()->get('uploaded_lez_rich'),
        ]);

        $request->session()->forget('uploaded_lez_rich');

        $admin = User::where('role', 'admin')->first();

        Mail::to($admin->email)
            ->send(new NewStudentRequestMail());

        return redirect()->route('lesson-requests.success');
    }

    public function storeSolution(Request $request, int $id)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $lezione = LessonRequest::findOrFail($id);

        $this->deleteFile($lezione->solution_file);

        $path = $this->saveFile($request->file('file'), 'lesson_requests/solution_files');

        $lezione->update([
            'solution_file' => $path
        ]);

        return redirect()->route('admin.lesson-requests.show', $lezione->id);
    }

    public function destroySolution(Request $request, int $id)
    {
        $lezione = LessonRequest::findOrFail($id);

        $this->deleteFile($lezione->solution_file);

        $lezione->update([
            'solution_file' => null
        ]);

        return redirect()->route('admin.lesson-requests.show', $lezione->id);
    }

    public function storePrice(Request $request, int $id)
    {
        $request->validate([
            'prezzo' => ['required', 'numeric', 'min:0'],
        ]);

        $lezione = LessonRequest::findOrFail($id);

        $lezione->update([
            'price' => $request->prezzo,
            'is_fulfilled' => 1
        ]);

        $user = $lezione->student->user;

        Mail::to($user->email)
            ->send(new LessonRequestFulfilledMail());

        return redirect()->route('admin.lesson-requests.show', $lezione->id);
    }
}
