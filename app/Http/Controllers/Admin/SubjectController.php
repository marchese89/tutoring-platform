<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ThemeArea;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('themeArea')
            ->withCount('courses')
            ->orderBy('name')
            ->get();

        $themeAreas = ThemeArea::orderBy('name')->get();

        return view('admin.teaching.subjects', compact('subjects', 'themeAreas'));
    }

    public function publicIndex(int $themeArea)
    {
        $subjects = Subject::where('theme_area_id', $themeArea)->get();

        return view('public.subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'theme_area_id' => 'required|exists:theme_areas,id',
            'name' => 'required|string|max:255',
        ]);

        Subject::create([
            'name' => $data['name'],
            'theme_area_id' => $data['theme_area_id'],
        ]);

        return redirect()->back()->with('success', __('admin.teaching.messages.subject_created'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subject = Subject::findOrFail($id);

        $subject->update([
            'name' => $data['name'],
        ]);

        return redirect()->back()->with('success', __('admin.teaching.messages.subject_updated'));
    }

    public function destroy(int $id)
    {
        $subject = Subject::withCount('courses')->findOrFail($id);

        if ($subject->courses_count > 0) {
            return back()->withErrors([
                'delete' => __('admin.teaching.messages.subject_has_courses'),
            ]);
        }

        $subject->delete();

        return redirect()->back()->with('success', __('admin.teaching.messages.subject_deleted'));
    }
}
