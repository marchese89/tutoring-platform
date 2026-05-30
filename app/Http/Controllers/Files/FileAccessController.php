<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\LessonOnRequest;
use App\Models\Invoice;
use App\Models\Student;
use App\Services\PurchaseService;

class FileAccessController extends Controller
{
    public function __invoke(Request $request, string $path)
    {
        // 🔒 Protezione base path traversal
        if (str_contains($path, '..')) {
            abort(403);
        }

        $fullPath = "private/$path";

        if (!Storage::exists($fullPath)) {
            abort(404);
        }

        // 🔍 Pre-carico tutto una sola volta
        $lessonPresentation = Lesson::where('presentation', $path)->first();
        $lessonFile = Lesson::where('lesson', $path)->first();
        $exerciseTrace = Exercise::where('trace', $path)->first();
        $exerciseExecution = Exercise::where('execution', $path)->first();

        // 👤 GUEST
        if (!Auth::check()) {
            if ($this->canAccessGuest($lessonPresentation, $lessonFile, $exerciseTrace, $exerciseExecution)) {
                return $this->serve($fullPath);
            }

            abort(404);
        }

        // 👤 USER
        $user = auth()->user();

        // 🟢 ADMIN
        if ($user->role === 'admin') {
            return $this->serve($fullPath);
        }

        // 🟡 STUDENT
        if ($user->role === 'student') {
            if ($this->canAccessStudent(
                $request,
                $user->student,
                $path,
                $lessonPresentation,
                $lessonFile,
                $exerciseTrace,
                $exerciseExecution
            )) {
                return $this->serve($fullPath);
            }
        }

        abort(404);
    }

    // =========================
    // 🔓 ACCESS LOGIC
    // =========================

    private function canAccessGuest($lessonPresentation, $lessonFile, $exerciseTrace, $exerciseExecution)
    {
        return
            $lessonPresentation !== null ||
            ($lessonFile !== null && $lessonFile->price === 0) ||
            $exerciseTrace !== null ||
            ($exerciseExecution !== null && $exerciseExecution->price === 0);
    }

    private function canAccessStudent(
        $request,
        $studente,
        $path,
        $lessonPresentation,
        $lessonFile,
        $exerciseTrace,
        $exerciseExecution
    ) {
        // 📘 Lezioni
        if ($lessonPresentation) {
            return true;
        }

        if ($lessonFile) {
            if ($lessonFile->price === 0) {
                return true;
            }

            if (PurchaseService::prodotto_acquistato($studente->id, $lessonFile->id, 0)) {
                return true;
            }
        }

        // 🧪 Esercizi
        if ($exerciseTrace) {
            return true;
        }

        if ($exerciseExecution) {
            if ($exerciseExecution->price === 0) {
                return true;
            }

            if (PurchaseService::prodotto_acquistato($studente->id, $exerciseExecution->id, 2)) {
                return true;
            }
        }

        // 📥 Upload temporaneo (sessione)
        if ($request->session()->exists('uploaded_lez_rich')) {
            return true;
        }

        // 📚 Lezioni su richiesta
        $lezRichTrace = LessonOnRequest::where('trace', $path)->first();
        if ($lezRichTrace) {
            return true;
        }

        $lezRichExec = LessonOnRequest::where('execution', $path)->first();
        if ($lezRichExec && $lezRichExec->paid == 1) {
            return true;
        }

        // 🧾 Fatture
        // $fattura = InvoiceSheet::where('file', $path)->first();
        $fattura = Invoice::where('path', $path)->first();

        if ($fattura) {
            return true;
        }

        return false;
    }

    // =========================
    // 📁 FILE RESPONSE
    // =========================

    private function serve(string $path)
    {
        return Storage::response($path);
    }
}
