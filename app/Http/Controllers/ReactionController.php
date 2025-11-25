<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    /**
     * Toggle reaction on a post (AJAX)
     */
    // App/Http/Controllers/ReactionController.php

public function toggle(Request $request, Content $post)
    {
        // only posts
        if (!$post->isPost()) {
            return response()->json(['error' => 'Can only react to posts'], 422);
        }

        $reactionType = $request->input('type'); // e.g. 'like' or 'confetti'
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // find any existing reaction by this user for this content
        $existing = Reaction::where('id_user', $user->id)
            ->where('id_content', $post->id)
            ->first();

        // If the user already reacted with the same type => remove (toggle off)
        if ($existing && $existing->type === $reactionType) {
            $existing->delete();
            $action = 'removed';
            $userReactionType = null;
        } else {
            // If exists but different type -> update it
            if ($existing) {
                $existing->update(['type' => $reactionType]);
            } else {
                Reaction::create([
                    'type' => $reactionType,
                    'id_user' => $user->id,
                    'id_content' => $post->id,
                ]);
            }
            $action = 'added_or_switched';
            $userReactionType = $reactionType;
        }

        // fresh counts
        $likesCount = $post->reactions()->where('type', 'like')->count();
        $confettiCount = $post->reactions()->where('type', 'confetti')->count();

        return response()->json([
            'action' => $action,
            'likes_count' => $likesCount,
            'confetti_count' => $confettiCount,
            'user_reaction' => $userReactionType,
        ]);
    }


    /**
     * Get reaction counts for a post (AJAX)
     */
    public function getCounts(Content $post)
    {
        $likesCount = $post->reactions()->where('type', 'like')->count();
        $confettiCount = $post->reactions()->where('type', 'confetti')->count();
        $userReaction = Auth::check() 
            ? $post->reactions()->where('id_user', Auth::id())->first()
            : null;

        return response()->json([
            'likes_count' => $likesCount,
            'confetti_count' => $confettiCount,
            'user_reaction' => $userReaction ? $userReaction->type : null,
        ]);
    }
}