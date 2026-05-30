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
        $corso = Course::query()->findOrFail($id);

        $student = $request->user()->student;

        $lezioni = Lesson::query()
            ->where('course_id', $corso->id)
            ->orderBy('number')
            ->get()
            ->filter(function ($lezione) use ($student) {
                return PurchaseService::prodotto_acquistato($student->id, $lezione->id, 0);
            })
            ->values();

        $esercizi = Exercise::query()
            ->where('course_id', $corso->id)
            ->orderBy('id')
            ->get()
            ->filter(function ($esercizio) use ($student) {
                return PurchaseService::prodotto_acquistato($student->id, $esercizio->id, 2);
            })
            ->values();

        return view('student.course', compact(
            'corso',
            'lezioni',
            'esercizi'
        ));
    }
}
