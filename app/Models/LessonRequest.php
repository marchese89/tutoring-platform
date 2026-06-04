<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'student_id',
        'request_file',
        'solution_file',
        'price',
        'is_fulfilled',
        'is_paid',
        'requested_at',
    ];

    protected $casts = [
        'is_fulfilled' => 'boolean',
        'is_paid' => 'boolean',
        'requested_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
