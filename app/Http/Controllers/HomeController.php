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
        $adminPhotoUrl = $this->assetUrl($admin?->photo_path);

        $reviews = Review::query()
            ->with('student.user')
            ->whereNotNull('review')
            ->where('review', '<>', '')
            ->latest()
            ->get();

        $averageRating = Review::query()
            ->whereNotNull('rating')
            ->avg('rating');

        return view('index', compact('admin', 'adminPhotoUrl', 'reviews', 'averageRating'));
    }

    public function about()
    {
        $admin = User::with('admin')->where('role', UserRole::ADMIN->value)->first()?->admin;
        $adminPhotoUrl = $this->assetUrl($admin?->photo_path);
        $certificates = Certificate::orderBy('id')->get();
        $certificateCount = $certificates->count();

        return view('public.about', compact('admin', 'adminPhotoUrl', 'certificates', 'certificateCount'));
    }

    private function assetUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
