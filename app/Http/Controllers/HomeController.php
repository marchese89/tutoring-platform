<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Review;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $admin = User::with('admin')->where('role', 'admin')->first()?->admin;

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
        $certificates = Certificate::orderBy('id')->get();

        return view('public.about', compact('certificates'));
    }
}
