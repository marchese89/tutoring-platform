<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PurchaseService;

class CourseController extends Controller
{
    public function index()
    {
        $materie = Subject::with('themeArea')->get();
        $corsi = Course::with('subject.themeArea')->get();
        return view('admin.teaching.create-course', compact('materie', 'corsi'));
    }

    public function publicIndex(int $subject)
    {
        $corsi = Course::where('subject_id', $subject)->get();

        return view('public.courses', compact('corsi'));
    }

    public function list()
    {
        $corsi = Course::with('subject.themeArea')->get();

        return view('admin.teaching.courses', compact('corsi'));
    }

    public function mieiCorsi(Request $request)
    {
        $studentId = $request->user()->student->id;

        $orderIds = Order::where('student_id', $studentId)->pluck('id');

        $productRows = OrderItem::whereIn('order_id', $orderIds)->get();

        $courseIds = [];

        foreach ($productRows as $row) {
            if ($row->product_type == 0) {
                $lesson = Lesson::find($row->product_id);
                if ($lesson) {
                    $courseIds[] = $lesson->course_id;
                }
            }

            if ($row->product_type == 2) {
                $exercise = Exercise::find($row->product_id);
                if ($exercise) {
                    $courseIds[] = $exercise->course_id;
                }
            }
        }

        $courseIds = array_unique($courseIds);

        $courses = Course::with('subject.themeArea')
            ->whereIn('id', $courseIds)
            ->get();

        return view('student.courses', compact('courses'));
    }

    public function edit(int $id)
    {
        $corso = Course::findOrFail($id);

        $lezioni = Lesson::where('course_id', $id)->orderBy('number')->get();
        $esercizi = Exercise::where('course_id', $id)->orderBy('id')->get();

        return view('admin.teaching.edit-course', compact('corso', 'lezioni', 'esercizi'));
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

        return back()->with('success', 'Corso creato');
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

        return back()->with('success', 'Corso aggiornato');
    }

    public function destroy(int $id)
    {
        Course::findOrFail($id)->delete();

        return back()->with('success', 'Corso eliminato');
    }

    public function show(int $id)
    {
        $course = Course::with(['lessons', 'exercises'])->findOrFail($id);

        $user = auth()->user();

        $studentId = $user?->student?->id;
        $isAdmin = $user?->role === 'admin';

        $lessons = $course->lessons->map(function ($lesson) use ($studentId, $isAdmin) {

            $purchased = false;

            if ($studentId) {
                $purchased = PurchaseService::isProductPurchased(
                    $studentId,
                    $lesson->id,
                    0
                );
            }

            $lesson->can_show =
                $lesson->price == 0 ||
                $purchased ||
                $isAdmin;

            return $lesson;
        });

        $exercises = $course->exercises->map(function ($exercise) use ($studentId, $isAdmin) {

            $purchased = false;

            if ($studentId) {
                $purchased = PurchaseService::isProductPurchased(
                    $studentId,
                    $exercise->id,
                    2
                );
            }

            $exercise->can_show =
                $exercise->price == 0 ||
                $purchased ||
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
