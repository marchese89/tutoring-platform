<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $admin = User::where('role', 'admin')->first()->admin;

        $feedbacks = Review::with('student.user')->get();

        $avg = $feedbacks->avg('rating');

        return view('index', compact('admin', 'feedbacks', 'avg'));
    }
}
