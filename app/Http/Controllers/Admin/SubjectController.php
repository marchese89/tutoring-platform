<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\ThemeArea;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('theme_area')->get();
        $themeAreas = ThemeArea::all();
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

        return redirect()->back()->with('success', 'Materia creata');
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

        return redirect()->back()->with('success', 'Materia aggiornata');
    }

    public function destroy(int $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Materia eliminata');
    }
}
