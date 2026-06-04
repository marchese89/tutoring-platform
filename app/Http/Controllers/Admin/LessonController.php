<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;

class LessonController extends Controller
{

    public function viewPresentation(int $id_corso, int $id_lezione)
    {
        $corso = Course::where('id', '=', $id_corso)->first();
        $lezione = Lesson::where('id', '=', $id_lezione)->first();

        return view('public.lesson-presentation', compact('corso', 'lezione'));
    }

    public function view(int $id_corso, int $id_lezione)
    {
        $corso = Course::where('id', '=', $id_corso)->first();
        $lezione = Lesson::where('id', '=', $id_lezione)->first();

        return view('public.lesson-content', compact('corso', 'lezione'));
    }

    public function create(int $id)
    {
        $corso = Course::where('id', '=', $id)->first();
        return view('admin.teaching.create-lesson', compact('id', 'corso'));
    }

    public function edit(int $id_corso, int $id_lezione)
    {
        $corso = Course::findOrFail($id_corso);
        $lezione = Lesson::where('course_id', $id_corso)->findOrFail($id_lezione);

        return view('admin.teaching.edit-lesson', compact('id_corso', 'id_lezione', 'corso', 'lezione'));
    }

    // ===============================
    // 📤 Upload presentazione (sessione)
    // ===============================
    public function uploadPresentation(Request $request)
    {
        $request->validate([
            'file-pres-lez' => 'required|file',
        ]);

        if ($old = $request->session()->pull('uploaded_pres_lez')) {
            Storage::disk('private')->delete($old);
        }

        $path = $request->file('file-pres-lez')
            ->store('lessons/presentations', 'private');

        $request->session()->put('uploaded_pres_lez', $path);

        return back();
    }

    public function deletePresentationSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_pres_lez')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    // ===============================
    // 📤 Upload file lezione (sessione)
    // ===============================
    public function uploadLessonFile(Request $request)
    {
        $request->validate([
            'file-lesson' => 'required|file',
        ]);

        if ($old = $request->session()->pull('uploaded_lesson')) {
            Storage::disk('private')->delete($old);
        }

        $path = $request->file('file-lesson')
            ->store('lessons/files', 'private');

        $request->session()->put('uploaded_lesson', $path);

        return back();
    }

    public function deleteLessonSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_lesson')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    // ===============================
    // 💾 Store lezione
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:courses,id',
            'numero' => 'required|integer',
            'titolo' => 'required|string',
            'prezzo' => 'required|numeric',
        ]);

        Lesson::create([
            'title' => $request->titolo,
            'number' => $request->numero,
            'course_id' => $request->id,
            'presentation_file' => $request->session()->get('uploaded_pres_lez'),
            'content_file' => $request->session()->get('uploaded_lesson'),
            'price' => $request->prezzo,
        ]);

        $request->session()->forget([
            'uploaded_pres_lez',
            'uploaded_lesson'
        ]);

        return redirect()->route('admin.courses.edit', $request->id);
    }

    // ===============================
    // ❌ Delete
    // ===============================
    public function destroy(Request $request, int $id)
    {
        $lesson = Lesson::findOrFail($id);

        Storage::disk('private')->delete([
            $lesson->presentation_file,
            $lesson->content_file
        ]);

        $courseId = $lesson->course_id;

        $lesson->delete();

        return redirect()->route('admin.courses.edit', $courseId);
    }

    // ===============================
    // 🔄 Update file presentazione
    // ===============================
    public function updatePresentation(Request $request, int $id)
    {
        $request->validate([
            'file-pres-lez' => 'required|file',
        ]);

        $lesson = Lesson::findOrFail($id);

        Storage::disk('private')->delete($lesson->presentation_file);

        $path = $request->file('file-pres-lez')
            ->store('lessons/presentations', 'private');

        $lesson->presentation_file = $path;
        $lesson->save();

        return back();
    }

    // ===============================
    // 🔄 Update file lezione
    // ===============================
    public function updateLessonFile(Request $request, int $id)
    {
        $request->validate([
            'file-lesson' => 'required|file',
        ]);

        $lesson = Lesson::findOrFail($id);

        Storage::disk('private')->delete($lesson->content_file);

        $path = $request->file('file-lesson')
            ->store('lessons/files', 'private');

        $lesson->content_file = $path;
        $lesson->save();

        return back();
    }

    // ===============================
    // ✏️ Update dati
    // ===============================
    public function update(Request $request, int $id)
    {
        $request->validate([
            'numero' => 'required|integer',
            'titolo' => 'required|string',
            'prezzo' => 'required|numeric',
        ]);

        $lesson = Lesson::findOrFail($id);

        $lesson->update([
            'title' => $request->titolo,
            'number' => $request->numero,
            'price' => $request->prezzo,
        ]);

        return back();
    }
}
