<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class ContentPolicy
{
    /**
     * Determine whether the user can view the content.
     */
    public function view(?User $user, Content $content): bool
    {
        // Users can view content unless it's deleted
        if ($content->isDeleted()) {
            return false;
        }

        // Get the content owner
        $owner = $content->ownerUser;
        
        if (!$owner) {
            return true; // If no owner, allow viewing
        }

        // Owner can always see their own content
        if ($user && $user->id === $owner->id) {
            return true;
        }

        if ($content->id_group) {
            $group = $content->group;

            // group doesnt exist
            if (!$group) {
                return false;
            }

            // public group
            if ($group->is_public) {
                if ($owner->is_public) {
                    return true;
                }

                if ($user && $user->isFollowing($owner)) {
                    return true;
                }

                return false;
            }

            // private group
            if (!$user) {
                // not authenticated
                return false;
            }

            $isGroupOwner = $group->owner === $user->id;
            $isGroupMember = $group->members()->where('id_user', $user->id)->exists();

            if (!$isGroupOwner || !$isGroupMember) {
                // user isnt member or owner
                return false;
            }

            // being part of the group, the user should see every post even if the other has a private profile
            return true;
        }

        // content not in a group

        // If owner's profile is public, everyone can see
        if ($owner->is_public) {
            return true;
        }

        // If not authenticated, cannot view private user's content
        if (!$user) {
            return false;
        }


        // For private profiles, only approved followers can view their content
        return $user->isFollowing($owner);
    }

    /**
     * Determine whether the user can create content.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create content
        return Auth::check();
    }

    /**
     * Determine whether the user can update the content.
     */
    public function update(User $user, Content $content): bool
    {
        // Adicionado: user->is_admin
        return !$content->isDeleted() && (
            $user->id === $content->owner || 
            ($content->group && $user->id === $content->group->owner) ||
            $user->is_admin
        );
    }

    /**
     * Determine whether the user can delete the content.
     */
    public function delete(User $user, Content $content): bool
    {
        // O utilizador pode apagar se for o dono, dono do grupo OU ADMIN
        return ($user->id === $content->owner || 
                ($content->group && $user->id === $content->group->owner) || 
                $user->is_admin); // <--- Adiciona isto
    }

    /**
     * Determine whether the user can react to the content.
     */
    public function react(User $user, Content $content): bool
    {
        // Users can react to any non-deleted content (including their own per BR09)
        return !$content->isDeleted();
    }
}
