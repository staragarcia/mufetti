<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $authUser, User $targetUser)
    {
        // Só pode apagar a própria conta
        return $authUser->id === $targetUser->id;
    }
}
