<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Support\UploadRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function viewPresentation(int $courseId, int $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);

        return view('public.lesson-presentation', compact('course', 'lesson'));
    }

    public function view(int $courseId, int $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);

        return view('public.lesson-content', compact('course', 'lesson'));
    }

    public function create(int $id)
    {
        $course = Course::find($id);

        return view('admin.teaching.create-lesson', compact('id', 'course'));
    }

    public function edit(int $courseId, int $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($lessonId);

        return view('admin.teaching.edit-lesson', compact('course', 'lesson'));
    }

    public function uploadPresentation(Request $request)
    {
        $request->validate([
            'presentation_file' => UploadRules::pdf(),
        ]);

        if ($oldPath = $request->session()->pull('uploaded_lesson_presentation')) {
            Storage::disk('private')->delete($oldPath);
        }

        $path = $request->file('presentation_file')
            ->store('lessons/presentations', 'private');

        $request->session()->put('uploaded_lesson_presentation', $path);

        return back();
    }

    public function deletePresentationSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_lesson_presentation')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    public function uploadLessonFile(Request $request)
    {
        $request->validate([
            'content_file' => UploadRules::pdf(),
        ]);

        if ($oldPath = $request->session()->pull('uploaded_lesson_content')) {
            Storage::disk('private')->delete($oldPath);
        }

        $path = $request->file('content_file')
            ->store('lessons/files', 'private');

        $request->session()->put('uploaded_lesson_content', $path);

        return back();
    }

    public function deleteLessonSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_lesson_content')) {
            Storage::disk('private')->delete($path);
        }

        return back();
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'course_id' => 'required|exists:courses,id',
                'number' => 'required|integer',
                'title' => 'required|string',
                'price' => 'required|numeric',
            ],
            [],
            [
                'number' => 'numero',
                'title' => 'titolo',
                'price' => 'prezzo',
            ]
        );

        Lesson::create([
            'title' => $validated['title'],
            'number' => $validated['number'],
            'course_id' => $validated['course_id'],
            'presentation_file' => $request->session()->get('uploaded_lesson_presentation'),
            'content_file' => $request->session()->get('uploaded_lesson_content'),
            'price' => $validated['price'],
        ]);

        $request->session()->forget([
            'uploaded_lesson_presentation',
            'uploaded_lesson_content',
        ]);

        return redirect()->route('admin.courses.edit', $validated['course_id']);
    }

    public function destroy(int $id)
    {
        $lesson = Lesson::findOrFail($id);

        Storage::disk('private')->delete([
            $lesson->presentation_file,
            $lesson->content_file,
        ]);

        $courseId = $lesson->course_id;
        $lesson->delete();

        return redirect()->route('admin.courses.edit', $courseId);
    }

    public function updatePresentation(Request $request, int $id)
    {
        $request->validate([
            'presentation_file' => UploadRules::pdf(),
        ]);

        $lesson = Lesson::findOrFail($id);

        Storage::disk('private')->delete($lesson->presentation_file);

        $lesson->presentation_file = $request->file('presentation_file')
            ->store('lessons/presentations', 'private');
        $lesson->save();

        return back();
    }

    public function updateLessonFile(Request $request, int $id)
    {
        $request->validate([
            'content_file' => UploadRules::pdf(),
        ]);

        $lesson = Lesson::findOrFail($id);

        Storage::disk('private')->delete($lesson->content_file);

        $lesson->content_file = $request->file('content_file')
            ->store('lessons/files', 'private');
        $lesson->save();

        return back();
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate(
            [
                'number' => 'required|integer',
                'title' => 'required|string',
                'price' => 'required|numeric',
            ],
            [],
            [
                'number' => 'numero',
                'title' => 'titolo',
                'price' => 'prezzo',
            ]
        );

        Lesson::findOrFail($id)->update($validated);

        return back();
    }
}
