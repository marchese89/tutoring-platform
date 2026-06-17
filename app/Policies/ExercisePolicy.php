<?php

namespace App\Policies;

use App\Http\Utility\CartItem;
use App\Models\Exercise;
use App\Models\User;
use App\Services\PurchaseService;

class ExercisePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Exercise $exercise): bool
    {
        $studentId = $user->student?->getKey();

        return $user->isStudent()
            && ((int) $exercise->price === 0
                || ($studentId
                    && PurchaseService::isProductPurchased(
                        $studentId,
                        $exercise->id,
                        CartItem::EXERCISE
                    )));
    }
}
