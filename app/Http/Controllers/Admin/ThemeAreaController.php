<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeArea;
use Illuminate\Http\Request;

class ThemeAreaController extends Controller
{
    public function index()
    {
        $themeAreas = ThemeArea::all();

        return view('admin.teaching.theme-areas', compact('themeAreas'));
    }

    public function publicIndex()
    {
        $themeAreas = ThemeArea::whereHas('subjects')
            ->orderBy('name')
            ->get();

        return view('public.theme-areas', compact('themeAreas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ThemeArea::create([
            'name' => $data['name'],
        ]);

        return back()->with('success', 'Area tematica creata');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $themeArea = ThemeArea::findOrFail($id);

        $themeArea->update([
            'name' => $data['name'],
        ]);

        return back()->with('success', 'Area tematica aggiornata');
    }

    public function destroy(int $id)
    {
        ThemeArea::findOrFail($id)->delete();

        return back()->with('success', 'Area tematica eliminata');
    }
}
