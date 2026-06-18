<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Mail\LessonRequestFulfilledMail;
use App\Models\LessonRequest;
use App\Support\PrivateUploadStorage;
use App\Support\UploadRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LessonRequestController extends Controller
{
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

    public function show(LessonRequest $lessonRequest)
    {
        return view('admin.students.lesson-request', compact('lessonRequest'));
    }

    public function storeSolution(Request $request, LessonRequest $lessonRequest)
    {
        $request->validate([
            'file' => UploadRules::pdf(),
        ]);

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

    public function destroySolution(Request $request, LessonRequest $lessonRequest)
    {
        PrivateUploadStorage::delete($lessonRequest->solution_file);

        $lessonRequest->update([
            'solution_file' => null,
        ]);

        return redirect()->route('admin.lesson-requests.show', $lessonRequest->id);
    }

    public function storePrice(Request $request, LessonRequest $lessonRequest)
    {
        $request->validate(
            ['price' => ['required', 'numeric', 'min:0']],
            [],
            ['price' => __('admin.students.price')]
        );

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
