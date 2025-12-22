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
            // 1. Nunca permitir apagar o utilizador anónimo
            if ($targetUser->id === User::ANONYMOUS_ID) {
                return false;
            }
    
            // 2. Nunca permitir que um Admin se apague a si próprio na página de perfil
            if ($targetUser->is_admin) {
                return false;
            }
    
            // 3. Permitir se for o próprio dono ou se o logado for Admin a apagar um user normal
            return $authMember->id === $targetUser->id || $authMember->is_admin;
        }
    }