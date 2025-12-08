<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="M10: Reactions",
 *     description="Endpoints to toggle and retrieve reactions (like/confetti) for posts."
 * )
 */
class ReactionController extends Controller
{
    /**
     * Toggle reaction on a post (AJAX)
     *
     * @OA\Post(
     *     path="/posts/{id}/reaction/toggle",
     *     summary="Toggle reaction on a post",
     *     description="Add, switch, or remove a reaction on a post. Returns updated counts.",
     *     tags={"M10: Reactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string", description="Reaction type: like or confetti")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reaction toggled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="action", type="string"),
     *             @OA\Property(property="likes_count", type="integer"),
     *             @OA\Property(property="confetti_count", type="integer"),
     *             @OA\Property(property="user_reaction", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Invalid post type")
     * )
     */
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
     *
     * @OA\Get(
     *     path="/posts/{id}/reaction/counts",
     *     summary="Get reaction counts for a post",
     *     description="Returns current likes and confetti counts, plus the user's reaction if authenticated.",
     *     tags={"M10: Reactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reaction counts retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="likes_count", type="integer"),
     *             @OA\Property(property="confetti_count", type="integer"),
     *             @OA\Property(property="user_reaction", type="string", nullable=true)
     *         )
     *     )
     * )
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

