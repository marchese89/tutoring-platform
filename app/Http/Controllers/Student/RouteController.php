<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function show(Request $request, int $id): View
    {
        $course = Course::query()->findOrFail($id);

        $student = $request->user()->student;

        $lessons = Lesson::query()
            ->where('course_id', $course->id)
            ->orderBy('number')
            ->get()
            ->filter(function ($lesson) use ($student) {
                return PurchaseService::isProductPurchased($student->id, $lesson->id, 0);
            })
            ->values();

        $exercises = Exercise::query()
            ->where('course_id', $course->id)
            ->orderBy('id')
            ->get()
            ->filter(function ($exercise) use ($student) {
                return PurchaseService::isProductPurchased($student->id, $exercise->id, 2);
            })
            ->values();

        return view('student.course', compact(
            'course',
            'lessons',
            'exercises'
        ));
    }
}
