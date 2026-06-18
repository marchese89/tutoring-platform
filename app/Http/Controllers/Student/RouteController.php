<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Utility\CartItem;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function __construct(private readonly PurchaseService $purchases) {}

    public function show(Request $request, Course $course): View
    {
        $student = $request->user()->student;
        $purchasedLessonIds = $this->purchases->purchasedProductIds(
            $student->id,
            CartItem::LESSON
        );
        $purchasedExerciseIds = $this->purchases->purchasedProductIds(
            $student->id,
            CartItem::EXERCISE
        );

        $lessons = Lesson::query()
            ->where('course_id', $course->id)
            ->whereIn('id', $purchasedLessonIds)
            ->orderBy('number')
            ->get();

        $exercises = Exercise::query()
            ->where('course_id', $course->id)
            ->whereIn('id', $purchasedExerciseIds)
            ->orderBy('id')
            ->get();

        return view('student.course', compact(
            'course',
            'lessons',
            'exercises'
        ));
    }
}
