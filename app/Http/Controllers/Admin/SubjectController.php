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
        $materie = Subject::with('theme_area')->get();
        $aree_t = ThemeArea::all();
        return view('admin.teaching.subjects', compact('materie', 'aree_t'));
    }

    public function publicIndex(int $themeArea)
    {
        $materie = Subject::where('theme_area_id', $themeArea)->get();
        return view('public.subjects', compact('materie'));
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

        $materia = Subject::findOrFail($id);

        $materia->update([
            'name' => $data['name'],
        ]);

        return redirect()->back()->with('success', 'Materia aggiornata');
    }

    public function destroy(int $id)
    {
        $materia = Subject::findOrFail($id);
        $materia->delete();

        return redirect()->back()->with('success', 'Materia eliminata');
    }
}
