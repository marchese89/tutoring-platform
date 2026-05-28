<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Matter;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Services\AcquistiService;

class CourseController extends Controller
{
    public function index()
    {
        $materie = Matter::with('theme_area')->get();
        $corsi = Course::with('matter.theme_area')->get();
        return view('admin.teaching.nuovo-corso', compact('materie', 'corsi'));
    }

    public function publicIndex(int $id_materia)
    {
        $corsi = Course::where('matter_id', $id_materia)->get();

        return view('public.corsi', compact('corsi'));
    }

    public function list()
    {
        $corsi = Course::with('matter.theme_area')->get();

        return view('admin.teaching.elenco-corsi', compact('corsi'));
    }

    public function mieiCorsi(Request $request)
    {
        $userId = $request->user()->id;

        $orderIds = Order::where('student_id', $userId)->pluck('id');

        $productRows = OrderProduct::whereIn('id_ordine', $orderIds)->get();

        $courseIds = [];

        foreach ($productRows as $row) {
            if ($row->tipo_prodotto == 0) {
                $lesson = Lesson::find($row->id_prodotto);
                if ($lesson) {
                    $courseIds[] = $lesson->course_id;
                }
            }

            if ($row->tipo_prodotto == 2) {
                $exercise = Exercise::find($row->id_prodotto);
                if ($exercise) {
                    $courseIds[] = $exercise->course_id;
                }
            }
        }

        $courseIds = array_unique($courseIds);

        $courses = Course::with('matter.theme_area')
            ->whereIn('id', $courseIds)
            ->get();

        return view('studente.elenco-corsi', compact('courses'));
    }

    public function edit(int $id)
    {
        $corso = Course::findOrFail($id);

        $lezioni = Lesson::where('course_id', $id)->orderBy('number')->get();
        $esercizi = Exercise::where('course_id', $id)->orderBy('id')->get();

        return view('admin.teaching.modifica-corso', compact('corso', 'lezioni', 'esercizi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matter_id' => 'required|exists:matters,id',
            'name' => 'required|string|max:255',
        ]);
        Course::create([
            'name' => $data['name'],
            'matter_id' => $data['matter_id'],
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
                $purchased = AcquistiService::prodotto_acquistato(
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
                $purchased = AcquistiService::prodotto_acquistato(
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

        return view('public.corso', compact(
            'course',
            'lessons',
            'exercises'
        ));
    }
}
