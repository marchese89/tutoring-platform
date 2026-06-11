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

        $feedbacks = Review::with('student.user')->get();

        $avg = $feedbacks->avg('rating');

        return view('index', compact('admin', 'feedbacks', 'avg'));
    }

    public function about()
    {
        $certificates = Certificate::orderBy('id')->get();

        return view('public.about', compact('certificates'));
    }
}
