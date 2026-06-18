<?php

namespace App\Http\Controllers\Public;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\NewStudentRequestMail;
use App\Models\LessonRequest;
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
}
