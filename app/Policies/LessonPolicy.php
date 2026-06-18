<?php

namespace App\Policies;

use App\Http\Utility\CartItem;
use App\Models\Lesson;
use App\Models\User;
use App\Services\PurchaseService;

class LessonPolicy
{
    public function __construct(private readonly PurchaseService $purchases) {}

    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Lesson $lesson): bool
    {
        $studentId = $user->student?->getKey();

        return $user->isStudent()
            && ((int) $lesson->price === 0
                || ($studentId
                    && $this->purchases->isProductPurchased(
                        $studentId,
                        $lesson->id,
                        CartItem::LESSON
                    )));
    }
}
