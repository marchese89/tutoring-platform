<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoReviewSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', env('SEED_STUDENT_EMAIL', 'student@example.com'))
            ->with('student')
            ->firstOrFail()
            ->student;

        Review::create([
            'student_id' => $student->id,
            'rating' => 5,
            'review' => 'Le spiegazioni sono chiare e molto pratiche.',
        ]);
    }
}
