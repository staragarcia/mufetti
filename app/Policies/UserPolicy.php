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
    public function delete(User $authMember, User $targetUser)
    {
        // Nunca apagar o utilizador anónimo
        if ($targetUser->id === User::ANONYMOUS_ID) {
            return false;
        }
    
        // Permitir se for o próprio dono OU se for um Admin a apagar outra pessoa
        return $authMember->id === $targetUser->id || $authMember->is_admin;
    }
}