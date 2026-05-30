<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThemeArea;

class ThemeAreaController extends Controller
{

    public function index()
    {
        $aree_t = ThemeArea::all();

        return view('admin.teaching.theme-areas', compact('aree_t'));
    }

    public function publicIndex()
    {
        $themeAreas = ThemeArea::whereHas('matter')
            ->orderBy('name')
            ->get();
        return view('public.theme-areas', compact('themeAreas'));
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        ThemeArea::create([
            'name' => $data['name']
        ]);

        return back()->with('success', 'Area tematica creata');
    }

    // UPDATE
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $themeArea = ThemeArea::findOrFail($id);

        $themeArea->update([
            'name' => $data['name']
        ]);

        return back()->with('success', 'Area tematica aggiornata');
    }

    // DELETE
    public function destroy(int $id)
    {
        ThemeArea::findOrFail($id)->delete();

        return back()->with('success', 'Area tematica eliminata');
    }
}
