<?php

namespace App\Policies;

use App\Http\Utility\CartItem;
use App\Models\Lesson;
use App\Models\User;
use App\Services\PurchaseService;

class LessonPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function view(User $user, Lesson $lesson): bool
    {
        $studentId = $user->student?->getKey();

        return $user->role === 'student'
            && ((int) $lesson->price === 0
                || ($studentId
                    && PurchaseService::isProductPurchased(
                        $studentId,
                        $lesson->id,
                        CartItem::LESSON
                    )));
    }
}
