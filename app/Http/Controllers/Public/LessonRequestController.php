<?php

namespace App\Http\Controllers\Public;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Mail\LessonRequestFulfilledMail;
use App\Mail\NewStudentRequestMail;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Student;
use App\Models\User;
use App\Support\PrivateUploadStorage;
use App\Support\UploadRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LessonRequestController extends Controller
{
    public function create(Request $request)
    {
        $uploadedRequestFile = $request->session()->get('uploaded_lesson_request_file');

        return view('public.lesson-request', [
            'uploadedRequestFile' => $uploadedRequestFile,
            'uploadedRequestFileUrl' => $uploadedRequestFile
                ? route('protected-files.show', ['path' => $uploadedRequestFile])
                : null,
        ]);
    }

    public function index()
    {
        $lessonRequests = LessonRequest::all()->map(fn (LessonRequest $lessonRequest) => [
            'id' => $lessonRequest->id,
            'title' => $lessonRequest->title,
            'requested_at' => DateHelper::format($lessonRequest->requested_at),
            'status_class' => $lessonRequest->is_fulfilled ? 'bg-success' : 'bg-danger',
            'show_url' => route('admin.lesson-requests.show', $lessonRequest->id),
        ]);

        return view('admin.students.lesson-requests', compact('lessonRequests'));
    }

    public function show(int $id)
    {
        $lessonRequest = LessonRequest::findOrFail($id);

        return view('admin.students.lesson-request', compact('lessonRequest'));
    }

    public function studentChats()
    {
        $chats = Chat::with(['student.user', 'latestMessage'])
            ->orderByDesc('created_at')
            ->get();

        $lessonTitles = Lesson::whereIn(
            'id',
            $chats->where('product_type', 0)->pluck('product_id')
        )->pluck('title', 'id');

        $exerciseTitles = Exercise::whereIn(
            'id',
            $chats->where('product_type', 2)->pluck('product_id')
        )->pluck('title', 'id');

        $lessonRequestTitles = LessonRequest::whereIn(
            'id',
            $chats->where('product_type', 5)->pluck('product_id')
        )->pluck('title', 'id');

        $chats->each(function (Chat $chat) use ($lessonTitles, $exerciseTitles, $lessonRequestTitles) {
            $chat->product_type_label = match ((int) $chat->product_type) {
                0 => 'Lezione',
                2 => 'Esercizio',
                5 => 'Lezione su Richiesta',
                default => '-',
            };

            $chat->product_name = match ((int) $chat->product_type) {
                0 => $lessonTitles->get($chat->product_id, '-'),
                2 => $exerciseTitles->get($chat->product_id, '-'),
                5 => $lessonRequestTitles->get($chat->product_id, '-'),
                default => '-',
            };

            $chat->student_name = $chat->student?->user
                ? trim($chat->student->user->name.' '.$chat->student->user->surname)
                : '-';

            $chat->has_unread_admin_message =
                $chat->latestMessage && (int) $chat->latestMessage->sender_role === 0;
        });

        return view('admin.students.chats', compact('chats'));
    }

    public function showChat($id)
    {
        $chat = Chat::findOrFail($id);

        $presentationFile = '';
        $contentFile = '';
        $title = '';

        switch ($chat->product_type) {
            case 0:
                $lesson = Lesson::find($chat->product_id);
                $presentationFile = $lesson?->presentation_file;
                $contentFile = $lesson?->content_file;
                $title =
                    'Lezione n. '.
                    $lesson?->id.
                    ', '.
                    $lesson?->title;
                break;

            case 2:
                $exercise = Exercise::find($chat->product_id);
                $presentationFile = $exercise?->prompt_file;
                $contentFile = $exercise?->solution_file;
                $title =
                    'Esercizio n. '.
                    $exercise?->id.
                    ', '.
                    $exercise?->title;
                break;

            case 5:
                $lessonRequest = LessonRequest::find($chat->product_id);
                $presentationFile = $lessonRequest?->request_file;
                $contentFile = $lessonRequest?->solution_file;
                $title =
                    'Lezione su richiesta n. '.
                    $lessonRequest?->id.
                    ', '.
                    $lessonRequest?->title;
                break;
        }

        $messages = ChatMessage::where('chat_id', $chat->id)
            ->orderBy('sent_at', 'asc')
            ->get();

        $student = Student::find($chat->student_id);
        $user = $student?->user;
        $studentName = trim(($user?->name ?? '').' '.($user?->surname ?? '')) ?: 'Studente';
        $enableEcho = true;

        return view('admin.students.chat', compact(
            'chat',
            'presentationFile',
            'contentFile',
            'title',
            'messages',
            'studentName',
            'enableEcho'
        ));
    }

    public function storeRequestFile(Request $request)
    {
        $request->validate([
            'file' => UploadRules::pdf(),
        ]);

        $oldPath = $request->session()->get('uploaded_lesson_request_file');
        $name = PrivateUploadStorage::store(
            $request->file('file'),
            'lesson_requests/request_files'
        );

        $request->session()->put('uploaded_lesson_request_file', $name);
        PrivateUploadStorage::delete($oldPath);

        return redirect()->route('lesson-requests.create');
    }

    public function destroyRequestFile(Request $request)
    {
        PrivateUploadStorage::delete(
            $request->session()->get('uploaded_lesson_request_file')
        );
        $request->session()->forget('uploaded_lesson_request_file');

        return redirect()->route('lesson-requests.create');
    }

    public function store(Request $request)
    {
        if (! $request->session()->has('uploaded_lesson_request_file')) {
            return redirect()
                ->route('lesson-requests.create')
                ->withErrors(['file' => 'Carica un file prima di inviare la richiesta.']);
        }

        $request->validate(
            ['title' => ['required', 'string', 'max:255']],
            [],
            ['title' => 'titolo']
        );

        LessonRequest::create([
            'title' => $request->input('title'),
            'student_id' => $request->user()->student->id,
            'request_file' => $request->session()->get('uploaded_lesson_request_file'),
        ]);

        $request->session()->forget('uploaded_lesson_request_file');

        $admin = User::where('role', 'admin')->first();

        Mail::to($admin->email)
            ->send(new NewStudentRequestMail);

        return redirect()->route('lesson-requests.success');
    }

    public function storeSolution(Request $request, int $id)
    {
        $request->validate([
            'file' => UploadRules::pdf(),
        ]);

        $lessonRequest = LessonRequest::findOrFail($id);

        $oldPath = $lessonRequest->solution_file;
        $path = PrivateUploadStorage::store(
            $request->file('file'),
            'lesson_requests/solution_files'
        );

        $lessonRequest->update([
            'solution_file' => $path,
        ]);

        PrivateUploadStorage::delete($oldPath);

        return redirect()->route('admin.lesson-requests.show', $lessonRequest->id);
    }

    public function destroySolution(Request $request, int $id)
    {
        $lessonRequest = LessonRequest::findOrFail($id);

        PrivateUploadStorage::delete($lessonRequest->solution_file);

        $lessonRequest->update([
            'solution_file' => null,
        ]);

        return redirect()->route('admin.lesson-requests.show', $lessonRequest->id);
    }

    public function storePrice(Request $request, int $id)
    {
        $request->validate(
            ['price' => ['required', 'numeric', 'min:0']],
            [],
            ['price' => 'prezzo']
        );

        $lessonRequest = LessonRequest::findOrFail($id);

        $lessonRequest->update([
            'price' => $request->price,
            'is_fulfilled' => 1,
        ]);

        $user = $lessonRequest->student->user;

        Mail::to($user->email)
            ->send(new LessonRequestFulfilledMail);

        return redirect()->route('admin.lesson-requests.show', $lessonRequest->id);
    }
}
