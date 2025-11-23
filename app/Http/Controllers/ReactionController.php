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
    public function toggle(Request $request, Content $post)
    {
        // make sure it's a post and not a comment
        if (!$post->isPost()) {
            return response()->json(['error' => 'Can only react to posts'], 422);
        }

        $reactionType = $request->input('type'); // like, confetti
        $user = Auth::user();

        // check if user already has this reaction on the post
        $existingReaction = Reaction::where('id_user', $user->id)
            ->where('id_content', $post->id)
            ->where('type', $reactionType)
            ->first();

        if ($existingReaction) {
            // remove reaction
            $existingReaction->delete();
            $action = 'removed';
        } else {
            // remove any existing reactions
            Reaction::where('id_user', $user->id)
                ->where('id_content', $post->id)
                ->delete();

            // add new reaction
            Reaction::create([
                'type' => $reactionType,
                'id_user' => $user->id,
                'id_content' => $post->id,
            ]);
            $action = 'added';
        }

        // reaction counts
        $likesCount = $post->reactions()->where('type', 'like')->count();
        $confettiCount = $post->reactions()->where('type', 'confetti')->count();
        $userReaction = $post->reactions()->where('id_user', $user->id)->first();

        return response()->json([
            'action' => $action,
            'type' => $reactionType,
            'likes_count' => $likesCount,
            'confetti_count' => $confettiCount,
            'user_reaction' => $userReaction ? $userReaction->type : null,
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