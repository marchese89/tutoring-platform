<?php

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    public static function teacherRating(): float
    {
        return (float) Review::avg('rating') ?? 0;
    }
}
