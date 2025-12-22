<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationCreated;


/**
 * @OA\Tag(
 *     name="M10: Reactions",
 *     description="Endpoints to toggle and retrieve reactions (like/confetti) for posts."
 * )
 */
class ReactionController extends Controller
{
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
                if ($post->owner !== $user->id) {
                    $notification = (object)[
                        'type' => 'reaction',
                        'receiver' => $post->owner,
                        'actor' => $user->id,
                        'name' => $user->username,
                    ];

                    event(new NotificationCreated($notification));
                }
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

    /**
     * Toggle reaction on a comment (AJAX)
     */
    public function toggleComment(Request $request, Content $comment)
    {
        // only comments
        if (!$comment->isComment()) {
            return response()->json(['error' => 'Can only react to comments'], 422);
        }

        $reactionType = $request->input('type');
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // find any existing reaction by this user for this comment
        $existing = Reaction::where('id_user', $user->id)
            ->where('id_content', $comment->id)
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
                    'id_content' => $comment->id,
                ]);
                if ($comment->owner !== $user->id) {
                    $notification = (object)[
                        'type' => 'reactionComment',
                        'receiver' => $comment->owner,
                        'actor' => $user->id,
                        'name' => $user->username,
                    ];

                    event(new NotificationCreated($notification));
                }
            }
            $action = 'added_or_switched';
            $userReactionType = $reactionType;
        }

        // fresh counts
        $likesCount = $comment->reactions()->where('type', 'like')->count();
        $confettiCount = $comment->reactions()->where('type', 'confetti')->count();

        return response()->json([
            'action' => $action,
            'likes_count' => $likesCount,
            'confetti_count' => $confettiCount,
            'user_reaction' => $userReactionType,
        ]);
    }

    /**
     * Get reaction counts for a comment (AJAX)
     */
    public function getCommentCounts(Content $comment)
    {
        $likesCount = $comment->reactions()->where('type', 'like')->count();
        $confettiCount = $comment->reactions()->where('type', 'confetti')->count();
        $userReaction = Auth::check()
            ? $comment->reactions()->where('id_user', Auth::id())->first()
            : null;

        return response()->json([
            'likes_count' => $likesCount,
            'confetti_count' => $confettiCount,
            'user_reaction' => $userReaction ? $userReaction->type : null,
        ]);
    }
}

