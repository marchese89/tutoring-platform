<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exercise;
use App\Support\UploadRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    public function create(int $course)
    {
        $course = Course::findOrFail($course);
        $id = $course->id;

        return view('admin.teaching.create-exercise', compact('id', 'course'));
    }

    public function edit(int $courseId, int $exerciseId)
    {
        $course = Course::findOrFail($courseId);
        $exercise = Exercise::where('course_id', $courseId)->findOrFail($exerciseId);

        return view('admin.teaching.edit-exercise', compact('course', 'exercise'));
    }

    public function viewTrace($courseId, $exerciseId)
    {
        $course = Course::find($courseId);
        $exercise = Exercise::find($exerciseId);

        return view('public.exercise-trace', compact('exercise', 'course'));
    }

    public function uploadTrace(Request $request)
    {
        $request->validate([
            'prompt_file' => UploadRules::pdf(),
        ]);

        if ($oldPath = $request->session()->pull('uploaded_exercise_prompt')) {
            Storage::disk('private')->delete($oldPath);
        }

        $path = $request->file('prompt_file')
            ->store('exercises/trace', 'private');

        $request->session()->put('uploaded_exercise_prompt', $path);

        return back();
    }

    public function clearTraceSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_exercise_prompt')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    public function uploadExecution(Request $request)
    {
        $request->validate([
            'solution_file' => UploadRules::pdf(),
        ]);

        if ($oldPath = $request->session()->pull('uploaded_exercise_solution')) {
            Storage::disk('private')->delete($oldPath);
        }

        $path = $request->file('solution_file')
            ->store('exercises/execution', 'private');

        $request->session()->put('uploaded_exercise_solution', $path);

        return back();
    }

    public function clearExecutionSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_exercise_solution')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'course_id' => 'required|exists:courses,id',
                'title' => 'required|string|max:255',
                'price' => 'required|numeric',
            ],
            [],
            [
                'title' => 'titolo',
                'price' => 'prezzo',
            ]
        );

        Exercise::create([
            'title' => $validated['title'],
            'course_id' => $validated['course_id'],
            'prompt_file' => $request->session()->get('uploaded_exercise_prompt'),
            'solution_file' => $request->session()->get('uploaded_exercise_solution'),
            'price' => $validated['price'],
        ]);

        $request->session()->forget([
            'uploaded_exercise_prompt',
            'uploaded_exercise_solution',
        ]);

        return redirect()->route('admin.courses.edit', $validated['course_id']);
    }

    public function destroy($id)
    {
        $exercise = Exercise::findOrFail($id);

        if ($exercise->prompt_file && Storage::disk('private')->exists($exercise->prompt_file)) {
            Storage::disk('private')->delete($exercise->prompt_file);
        }

        if ($exercise->solution_file && Storage::disk('private')->exists($exercise->solution_file)) {
            Storage::disk('private')->delete($exercise->solution_file);
        }

        $exercise->delete();

        return back();
    }

    public function updateTrace(Request $request, $id)
    {
        $request->validate([
            'prompt_file' => UploadRules::pdf(),
        ]);

        $exercise = Exercise::findOrFail($id);

        Storage::disk('private')->delete($exercise->prompt_file);

        $exercise->update([
            'prompt_file' => $request->file('prompt_file')
                ->store('exercises/trace', 'private'),
        ]);

        return back();
    }

    public function updateExecution(Request $request, $id)
    {
        $request->validate([
            'solution_file' => UploadRules::pdf(),
        ]);

        $exercise = Exercise::findOrFail($id);

        Storage::disk('private')->delete($exercise->solution_file);

        $exercise->update([
            'solution_file' => $request->file('solution_file')
                ->store('exercises/execution', 'private'),
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'title' => 'required|string|max:255',
                'price' => 'required|numeric',
            ],
            [],
            [
                'title' => 'titolo',
                'price' => 'prezzo',
            ]
        );

        Exercise::findOrFail($id)->update($validated);

        return back();
    }
}
