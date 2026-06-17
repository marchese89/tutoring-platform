<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Support\PrivateUploadStorage;
use App\Support\UploadRules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function create(Request $request, int $id)
    {
        $course = Course::findOrFail($id);
        $presentationPath = $request->session()->get('uploaded_lesson_presentation');
        $contentPath = $request->session()->get('uploaded_lesson_content');

        return view('admin.teaching.create-lesson', [
            'id' => $id,
            'course' => $course,
            'presentationUploaded' => (bool) $presentationPath,
            'presentationUrl' => $presentationPath
                ? route('protected-files.show', ['path' => $presentationPath])
                : null,
            'contentUploaded' => (bool) $contentPath,
            'contentUrl' => $contentPath
                ? route('protected-files.show', ['path' => $contentPath])
                : null,
        ]);
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

        $oldPath = $request->session()->get('uploaded_lesson_presentation');
        $path = PrivateUploadStorage::store(
            $request->file('presentation_file'),
            'lessons/presentations'
        );

        $request->session()->put('uploaded_lesson_presentation', $path);
        PrivateUploadStorage::delete($oldPath);

        return back();
    }

    public function deletePresentationSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_lesson_presentation')) {
            PrivateUploadStorage::delete($path);
        }

        return back();
    }

    public function uploadLessonFile(Request $request)
    {
        $request->validate([
            'content_file' => UploadRules::pdf(),
        ]);

        $oldPath = $request->session()->get('uploaded_lesson_content');
        $path = PrivateUploadStorage::store(
            $request->file('content_file'),
            'lessons/files'
        );

        $request->session()->put('uploaded_lesson_content', $path);
        PrivateUploadStorage::delete($oldPath);

        return back();
    }

    public function deleteLessonSession(Request $request)
    {
        if ($path = $request->session()->pull('uploaded_lesson_content')) {
            PrivateUploadStorage::delete($path);
        }

        return back();
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'course_id' => 'required|exists:courses,id',
                'number' => [
                    'required',
                    'integer',
                    Rule::unique('lessons', 'number')
                        ->where(fn ($query) => $query->where('course_id', $request->input('course_id'))),
                ],
                'title' => 'required|string',
                'price' => 'required|numeric',
            ],
            [],
            [
                'number' => __('admin.teaching.validation.number'),
                'title' => __('admin.teaching.validation.title'),
                'price' => __('admin.teaching.validation.price'),
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

        PrivateUploadStorage::delete([
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

        $oldPath = $lesson->presentation_file;
        $lesson->presentation_file = PrivateUploadStorage::store(
            $request->file('presentation_file'),
            'lessons/presentations'
        );
        $lesson->save();

        PrivateUploadStorage::delete($oldPath);

        return back();
    }

    public function updateLessonFile(Request $request, int $id)
    {
        $request->validate([
            'content_file' => UploadRules::pdf(),
        ]);

        $lesson = Lesson::findOrFail($id);

        $oldPath = $lesson->content_file;
        $lesson->content_file = PrivateUploadStorage::store(
            $request->file('content_file'),
            'lessons/files'
        );
        $lesson->save();

        PrivateUploadStorage::delete($oldPath);

        return back();
    }

    public function update(Request $request, int $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate(
            [
                'number' => [
                    'required',
                    'integer',
                    Rule::unique('lessons', 'number')
                        ->where(fn ($query) => $query->where('course_id', $lesson->course_id))
                        ->ignore($lesson->id),
                ],
                'title' => 'required|string',
                'price' => 'required|numeric',
            ],
            [],
            [
                'number' => __('admin.teaching.validation.number'),
                'title' => __('admin.teaching.validation.title'),
                'price' => __('admin.teaching.validation.price'),
            ]
        );

        $lesson->update($validated);

        return back();
    }
}
