<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardPolicy
{
    /**
     * Determine whether the user can view a specific card.
     */
    public function view(User $user, Card $card): bool
    {
        // Only the owner can view the card.
        return $user->id === $card->user_id;
    }

    /**
     * Determine whether the user can view ANY cards.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view their own list of cards.
        return Auth::check();
    }

    /**
     * Determine whether the user can create new cards.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete a card.
     */
    public function delete(User $user, Card $card): bool
    {
        return $user->id === $card->user_id;
    }
}
