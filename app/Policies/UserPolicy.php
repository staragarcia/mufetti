<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view another user's profile
     */
    public function view(?User $authUser, User $targetUser): bool
    {
        // If profile is public, anyone can view
        if ($targetUser->is_public) {
            return true;
        }

        // If not authenticated, cannot view private profiles
        if (!$authUser) {
            return false;
        }

        // Owner can always view their own profile
        if ($authUser->id === $targetUser->id) {
            return true;
        }

        // For private profiles, only followers can view
        return $authUser->isFollowing($targetUser);
    }

    public function delete(User $authUser, User $targetUser)
    {
        // Só pode apagar a própria conta
        return $authUser->id === $targetUser->id;
    }
}
