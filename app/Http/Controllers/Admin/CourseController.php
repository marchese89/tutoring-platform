<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Subject;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('themeArea')
            ->orderBy('name')
            ->get();

        $courses = Course::with('subject.themeArea')
            ->withCount(['lessons', 'exercises'])
            ->orderBy('name')
            ->get();

        return view('admin.teaching.create-course', compact('subjects', 'courses'));
    }

    public function publicIndex(int $subject)
    {
        $courses = Course::where('subject_id', $subject)->get();

        return view('public.courses', compact('courses'));
    }

    public function list()
    {
        $courses = Course::with('subject.themeArea')
            ->orderBy('name')
            ->get();

        return view('admin.teaching.courses', compact('courses'));
    }

    public function purchasedCourses(Request $request)
    {
        $studentId = $request->user()->student->id;

        $lessonCourseIds = Lesson::whereIn(
            'id',
            PurchaseService::purchasedProductIds($studentId, 0)
        )->pluck('course_id');

        $exerciseCourseIds = Exercise::whereIn(
            'id',
            PurchaseService::purchasedProductIds($studentId, 2)
        )->pluck('course_id');

        $courseIds = $lessonCourseIds
            ->merge($exerciseCourseIds)
            ->unique()
            ->values();

        $courses = Course::with('subject.themeArea')
            ->whereIn('id', $courseIds)
            ->get();

        return view('student.courses', compact('courses'));
    }

    public function edit(int $id)
    {
        $course = Course::findOrFail($id);

        $lessons = Lesson::where('course_id', $id)->orderBy('number')->get();
        $exercises = Exercise::where('course_id', $id)->orderBy('id')->get();

        return view('admin.teaching.edit-course', compact('course', 'lessons', 'exercises'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
        ]);
        Course::create([
            'name' => $data['name'],
            'subject_id' => $data['subject_id'],
        ]);

        return back()->with('success', __('admin.teaching.messages.course_created'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'name' => $data['name'],
        ]);

        return back()->with('success', __('admin.teaching.messages.course_updated'));
    }

    public function destroy(int $id)
    {
        $course = Course::withCount(['lessons', 'exercises'])->findOrFail($id);

        if ($course->lessons_count > 0 || $course->exercises_count > 0) {
            return back()->withErrors([
                'delete' => __('admin.teaching.messages.course_has_content'),
            ]);
        }

        $course->delete();

        return back()->with('success', __('admin.teaching.messages.course_deleted'));
    }

    public function show(int $id)
    {
        $course = Course::with(['lessons', 'exercises'])->findOrFail($id);

        $user = auth()->user();

        $studentId = $user?->student?->id;
        $isAdmin = $user?->isAdmin() ?? false;
        $purchasedLessonIds = $studentId
            ? PurchaseService::purchasedProductIds($studentId, 0)
            : collect();
        $purchasedExerciseIds = $studentId
            ? PurchaseService::purchasedProductIds($studentId, 2)
            : collect();

        $lessons = $course->lessons->map(function ($lesson) use ($purchasedLessonIds, $isAdmin) {
            $lesson->can_show =
                $lesson->price == 0 ||
                $purchasedLessonIds->contains($lesson->id) ||
                $isAdmin;

            return $lesson;
        });

        $exercises = $course->exercises->map(function ($exercise) use ($purchasedExerciseIds, $isAdmin) {
            $exercise->can_show =
                $exercise->price == 0 ||
                $purchasedExerciseIds->contains($exercise->id) ||
                $isAdmin;

            return $exercise;
        });

        return view('public.course', compact(
            'course',
            'lessons',
            'exercises'
        ));
    }
}
