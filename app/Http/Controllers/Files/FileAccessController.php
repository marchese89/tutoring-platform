<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Invoice;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\Student;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileAccessController extends Controller
{
    public function __invoke(Request $request, string $path)
    {
        if ($this->isUnsafePath($path)) {
            abort(403);
        }

        if (! Storage::disk('private')->exists($path)) {
            abort(404);
        }

        $lessonPresentation = Lesson::where('presentation_file', $path)->first();
        $lessonContent = Lesson::where('content_file', $path)->first();
        $exercisePrompt = Exercise::where('prompt_file', $path)->first();
        $exerciseSolution = Exercise::where('solution_file', $path)->first();

        if (! Auth::check()) {
            if ($this->canAccessGuest(
                $lessonPresentation,
                $lessonContent,
                $exercisePrompt,
                $exerciseSolution
            )) {
                return $this->serve($path);
            }

            abort(404);
        }

        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->serve($path);
        }

        if (
            $user->isStudent()
            && $this->canAccessStudent(
                $request,
                $user->student,
                $path,
                $lessonPresentation,
                $lessonContent,
                $exercisePrompt,
                $exerciseSolution
            )
        ) {
            return $this->serve($path);
        }

        abort(404);
    }

    private function canAccessGuest(
        ?Lesson $lessonPresentation,
        ?Lesson $lessonContent,
        ?Exercise $exercisePrompt,
        ?Exercise $exerciseSolution
    ): bool {
        return
            $lessonPresentation !== null
            || ($lessonContent !== null && $lessonContent->price == 0)
            || $exercisePrompt !== null
            || ($exerciseSolution !== null && $exerciseSolution->price == 0);
    }

    private function canAccessStudent(
        Request $request,
        Student $student,
        string $path,
        ?Lesson $lessonPresentation,
        ?Lesson $lessonContent,
        ?Exercise $exercisePrompt,
        ?Exercise $exerciseSolution
    ): bool {
        if ($lessonPresentation) {
            return true;
        }

        if (
            $lessonContent
            && (
                $lessonContent->price == 0
                || PurchaseService::isProductPurchased($student->id, $lessonContent->id, 0)
            )
        ) {
            return true;
        }

        if ($exercisePrompt) {
            return true;
        }

        if (
            $exerciseSolution
            && (
                $exerciseSolution->price == 0
                || PurchaseService::isProductPurchased($student->id, $exerciseSolution->id, 2)
            )
        ) {
            return true;
        }

        if ($request->session()->get('uploaded_lesson_request_file') === $path) {
            return true;
        }

        if (
            LessonRequest::where('student_id', $student->id)
                ->where('request_file', $path)
                ->exists()
        ) {
            return true;
        }

        if (
            LessonRequest::where('student_id', $student->id)
                ->where('solution_file', $path)
                ->where('is_paid', true)
                ->exists()
        ) {
            return true;
        }

        return Invoice::where('file_path', $path)
            ->where(function ($query) use ($student) {
                $query->where('student_id', $student->id)
                    ->orWhereHas('order', fn ($order) => $order->where('student_id', $student->id));
            })
            ->exists();
    }

    private function serve(string $path)
    {
        return Storage::disk('private')->response($path);
    }

    private function isUnsafePath(string $path): bool
    {
        if ($path === '' || str_contains($path, "\0") || str_contains($path, '\\')) {
            return true;
        }

        foreach (explode('/', $path) as $segment) {
            if ($segment === '.' || $segment === '..') {
                return true;
            }
        }

        return false;
    }
}
