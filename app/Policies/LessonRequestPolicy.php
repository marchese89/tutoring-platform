<?php

namespace App\Policies;

use App\Models\LessonRequest;
use App\Models\User;

class LessonRequestPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function view(User $user, LessonRequest $lessonRequest): bool
    {
        return $user->role === 'student'
            && $user->student?->getKey() === $lessonRequest->student_id;
    }

    public function purchase(User $user, LessonRequest $lessonRequest): bool
    {
        return $this->view($user, $lessonRequest)
            && ! $lessonRequest->is_paid;
    }
}
