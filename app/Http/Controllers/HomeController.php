<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Certificate;
use App\Models\Review;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $admin = User::with('admin')->where('role', UserRole::ADMIN->value)->first()?->admin;

        $reviews = Review::query()
            ->with('student.user')
            ->whereNotNull('review')
            ->where('review', '<>', '')
            ->latest()
            ->get();

        $averageRating = Review::query()
            ->whereNotNull('rating')
            ->avg('rating');

        return view('index', compact('admin', 'reviews', 'averageRating'));
    }

    public function about()
    {
        $admin = User::with('admin')->where('role', UserRole::ADMIN->value)->first()?->admin;
        $certificates = Certificate::orderBy('id')->get();
        $certificateCount = $certificates->count();

        return view('public.about', compact('admin', 'certificates', 'certificateCount'));
    }
}
