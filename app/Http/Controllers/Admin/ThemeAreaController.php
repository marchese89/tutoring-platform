<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeArea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThemeAreaController extends Controller
{
    public function index()
    {
        $themeAreas = ThemeArea::withCount('subjects')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.teaching.theme-areas', compact('themeAreas'));
    }

    public function publicIndex()
    {
        $themeAreas = ThemeArea::whereHas('subjects')
            ->orderBy('name')
            ->paginate(12);

        return view('public.theme-areas', compact('themeAreas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('theme_areas', 'name')],
        ]);

        ThemeArea::create([
            'name' => $data['name'],
        ]);

        return back()->with('success', __('admin.teaching.messages.theme_area_created'));
    }

    public function update(Request $request, ThemeArea $themeArea)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('theme_areas', 'name')->ignore($themeArea)],
        ]);

        $themeArea->update([
            'name' => $data['name'],
        ]);

        return back()->with('success', __('admin.teaching.messages.theme_area_updated'));
    }

    public function destroy(ThemeArea $themeArea)
    {
        $themeArea->loadCount('subjects');

        if ($themeArea->subjects_count > 0) {
            return back()->withErrors([
                'delete' => __('admin.teaching.messages.theme_area_has_subjects'),
            ]);
        }

        $themeArea->delete();

        return back()->with('success', __('admin.teaching.messages.theme_area_deleted'));
    }
}
