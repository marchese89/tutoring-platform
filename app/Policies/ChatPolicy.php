<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Chat $chat): bool
    {
        return $user->isStudent()
            && $user->student?->getKey() === $chat->student_id;
    }

    public function sendMessage(User $user, Chat $chat): bool
    {
        return $this->view($user, $chat);
    }
}
