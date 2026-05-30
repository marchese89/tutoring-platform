<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{

    public function viewTrace($id_corso, $id_esercizio)
    {
        $corso = Course::where('id', '=', $id_corso)->first();
        $esercizio = Exercise::where('id', '=', $id_esercizio)->first();

        return view('public.exercise-trace', compact('esercizio', 'corso'));
    }

    // =========================
    // UPLOAD TRACE (temporaneo)
    // =========================
    public function uploadTrace(Request $request)
    {
        $request->validate([
            'file-trace-ex' => 'required|file',
        ]);

        if ($old = $request->session()->pull('uploaded_trace_ex')) {
            Storage::disk('private')->delete($old);
        }

        $path = $request->file('file-trace-ex')
            ->store('exercises/trace', 'private');

        $request->session()->put('uploaded_trace_ex', $path);

        return back();
    }

    // =========================
    // DELETE TRACE SESSION
    // =========================
    public function clearTraceSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_trace_ex')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    // =========================
    // UPLOAD EXECUTION (temporaneo)
    // =========================
    public function uploadExecution(Request $request)
    {
        $request->validate([
            'file-ex' => 'required|file',
        ]);

        if ($old = $request->session()->pull('uploaded_ex')) {
            Storage::disk('private')->delete($old);
        }

        $path = $request->file('file-ex')
            ->store('exercises/execution', 'private');

        $request->session()->put('uploaded_ex', $path);

        return back();
    }

    // =========================
    // DELETE EXECUTION SESSION
    // =========================
    public function clearExecutionSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_ex')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    // =========================
    // CREATE EXERCISE
    // =========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:courses,id',
            'titolo' => 'required|string|max:255',
            'prezzo' => 'required|numeric',
        ]);

        Exercise::create([
            'title' => $data['titolo'],
            'course_id' => $data['id'],
            'trace' => $request->session()->get('uploaded_trace_ex'),
            'execution' => $request->session()->get('uploaded_ex'),
            'price' => $data['prezzo'],
        ]);

        $request->session()->forget([
            'uploaded_trace_ex',
            'uploaded_ex'
        ]);

        return redirect()->route('admin.courses.edit', $data['id']);
    }

    // =========================
    // DELETE EXERCISE
    // =========================
    public function destroy(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        if ($exercise->trace && Storage::disk('private')->exists($exercise->trace)) {
            Storage::disk('private')->delete($exercise->trace);
        }

        if ($exercise->execution && Storage::disk('private')->exists($exercise->execution)) {
            Storage::disk('private')->delete($exercise->execution);
        }
        $exercise->delete();

        return back();
    }

    // =========================
    // UPDATE TRACE FILE
    // =========================
    public function updateTrace(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        Storage::disk('private')->delete($exercise->trace);

        $path = $request->file('file-trace-ex')
            ->store('exercises/trace', 'private');

        $exercise->update([
            'trace' => $path
        ]);

        return back();
    }

    // =========================
    // UPDATE EXECUTION FILE
    // =========================
    public function updateExecution(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        Storage::disk('private')->delete($exercise->execution);

        $path = $request->file('file-ex')
            ->store('exercises/execution', 'private');

        $exercise->update([
            'execution' => $path
        ]);

        return back();
    }

    // =========================
    // UPDATE META
    // =========================
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'titolo' => 'required|string|max:255',
            'prezzo' => 'required|numeric',
        ]);

        $exercise = Exercise::findOrFail($id);

        $exercise->update([
            'title' => $data['titolo'],
            'price' => $data['prezzo'],
        ]);

        return back();
    }
}
