<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Services\AcquistiService;


class RouteController extends Controller
{
    public function show(int $id)
    {
        $corso = Course::findOrFail($id);

        $student = request()->user()->student;

        $lezioni = Lesson::where('course_id', $corso->id)
            ->orderBy('number')
            ->get()
            ->filter(function ($lezione) use ($student) {
                return AcquistiService::prodotto_acquistato($student->id, $lezione->id, 0);
            })
            ->values();

        $esercizi = Exercise::where('course_id', $corso->id)
            ->orderBy('id')
            ->get()
            ->filter(function ($esercizio) use ($student) {
                return AcquistiService::prodotto_acquistato($student->id, $esercizio->id, 2);
            })
            ->values();

        return view('studente.corso', compact(
            'corso',
            'lezioni',
            'esercizi'
        ));
    }
}
