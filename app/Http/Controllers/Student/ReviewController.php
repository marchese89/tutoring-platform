<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function show(Request $request): View
    {
        $feedback = Feedback::where('student_id', $request->user()->student->id)->first();

        return view('student.review', [
            'rating' => (int) ($feedback?->punteggio ?? 0),
            'review' => $feedback?->recensione ?? '',
        ]);
    }
}
