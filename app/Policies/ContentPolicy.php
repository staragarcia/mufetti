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
    public function view(User $user, Content $content): bool
    {
        // Users can view content unless it's deleted
        if ($content->isDeleted()) {
            return false;
        }

        // For private profiles, only approved followers can view their content
        // (We'll implement this later based on your BR02)
        return true;
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
        // (User can only update their own content OR group owner) AND it must not be deleted
        return !$content->isDeleted() && ($user->id === $content->owner || ($content->group && $user->id === $content->group->owner)) ;
    }

    /**
     * Determine whether the user can delete the content.
     */
    public function delete(User $user, Content $content): bool
    {
        // (User can only delete their own content OR group owner )AND it must not already be deleted
        return !$content->isDeleted() && ($user->id === $content->owner || ($content->group && $user->id === $content->group->owner))  ;
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
