<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_id',
        'prompt_file',
        'solution_file',
        'price',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
