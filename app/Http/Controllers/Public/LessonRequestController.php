<?php

namespace App\Http\Controllers\Public;

use App\Enums\ChatSenderRole;
use App\Enums\ProductType;
use App\Enums\UserRole;
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
            'isAuthenticated' => $request->user() !== null,
            'userCanSubmit' => $request->user()?->isStudent() ?? false,
        ]);
    }

    public function index()
    {
        $lessonRequests = LessonRequest::query()
            ->orderByDesc('requested_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->through(fn (LessonRequest $lessonRequest) => [
                'id' => $lessonRequest->id,
                'title' => $lessonRequest->title,
                'date' => DateHelper::format($lessonRequest->requested_at),
                'status_variant' => $lessonRequest->is_fulfilled ? 'success' : 'danger',
                'status_label' => $lessonRequest->is_fulfilled
                    ? __('admin.students.request_fulfilled')
                    : __('admin.students.request_to_fulfill'),
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

    public function showChat(int $id)
    {
        $chat = Chat::findOrFail($id);

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
                ->withErrors(['file' => __('public.lesson_request.file_required_before_submit')]);
        }

        $request->validate(
            ['title' => ['required', 'string', 'max:255']],
            [],
            ['title' => __('public.lesson_request.request_title')]
        );

        LessonRequest::create([
            'title' => $request->input('title'),
            'student_id' => $request->user()->student->id,
            'request_file' => $request->session()->get('uploaded_lesson_request_file'),
        ]);

        $request->session()->forget('uploaded_lesson_request_file');

        $admin = User::where('role', UserRole::ADMIN->value)->first();

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
            ['price' => __('admin.students.price')]
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
