<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Events\NotificationCreated;

/**
 * @OA\Tag(
 *     name="M08: Comments",
 *     description="Endpoints for creating, viewing, editing and deleting comments on posts."
 * )
 */
class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @OA\Post(
     *     path="/posts/{post}/comments",
     *     summary="Create a new comment",
     *     description="Stores a new comment on a post.",
     *     tags={"M08: Comments"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"description"},
     *                 @OA\Property(property="description", type="string", description="Comment content")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after creating comment"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function store(Request $request, Content $post)
    {
        // Ensure we're commenting on a post
        if (!$post->isPost()) {
            abort(404, 'Post not found.');
        }

        // Validate the request data
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        // Create the comment
        $comment = Content::create([
            'type' => 'comment',
            'description' => $validated['description'],
            'owner' => Auth::id(),
            'reply_to' => $post->id,
            'id_group' => $post->id_group, // Inherit group from parent post
        ]);

        if ($post->owner !== Auth::id()) {
            $notification = (object)[
                'type' => 'comment',
                'receiver' => $post->owner,
                'actor' => Auth::id(),
            ];

            event(new NotificationCreated($notification));
        }
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('ownerUser'),
                'message' => 'Comment posted successfully!'
            ]);
        }



        return redirect()->route('posts.show', $post)
            ->with('success', 'Comment posted successfully!');
    }

    /**
     * Reply to a comment (nested reply).
     *
     * @OA\Post(
     *     path="/comments/{comment}/reply",
     *     summary="Reply to a comment",
     *     description="Creates a nested reply to an existing comment.",
     *     tags={"M08: Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         description="Comment ID to reply to",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"description"},
     *                 @OA\Property(property="description", type="string", description="Reply content")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after creating reply"),
     *     @OA\Response(response=404, description="Comment not found")
     * )
     */
    public function reply(Request $request, Content $comment)
    {
        // Ensure we're replying to a comment
        if (!$comment->isComment()) {
            abort(404, 'Comment not found.');
        }

        // Prevent nested replies - only allow replies to top-level comments
        if ($comment->parent && $comment->parent->isComment()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reply to a reply. Please reply to the main comment instead.'
                ], 403);
            }

            return redirect()->back()
                ->with('error', 'Cannot reply to a reply. Please reply to the main comment instead.');
        }

        // Validate the request data
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        // Create the nested reply
        $reply = Content::create([
            'type' => 'comment',
            'description' => $validated['description'],
            'owner' => Auth::id(),
            'reply_to' => $comment->id, // Reply to the comment
            'id_group' => $comment->id_group, // Inherit group
        ]);


        if ($comment->owner !== Auth::id()) {
            $notification = (object)[
                'type' => 'comment',
                'receiver' => $comment->owner,
                'actor' => Auth::id(),
            ];

            event(new NotificationCreated($notification));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reply' => $reply->load('ownerUser'),
                'message' => 'Reply posted successfully!'
            ]);
        }

        // Redirect back to the original post
        $originalPost = $comment->parent;
        while ($originalPost && $originalPost->isComment()) {
            $originalPost = $originalPost->parent;
        }

        return redirect()->route('posts.show', $originalPost ?? $comment->reply_to)
            ->with('success', 'Reply posted successfully!');
    }

    /**
     * Update the specified comment in storage.
     *
     * @OA\Put(
     *     path="/comments/{comment}",
     *     summary="Update a comment",
     *     description="Only comment owner can update the comment.",
     *     tags={"M08: Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         description="Comment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"description"},
     *                 @OA\Property(property="description", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after updating comment"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function update(Request $request, Content $comment)
    {
        // Ensure we're updating a comment
        if (!$comment->isComment()) {
            abort(404, 'Comment not found.');
        }

        // Authorization - user can only update their own comments
        Gate::authorize('update', $comment);

        // Validate the request data
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        // Update the comment
        $comment->update([
            'description' => $validated['description'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Comment updated successfully!'
            ]);
        }

        // Redirect back to the original post
        $originalPost = $comment->parent;
        while ($originalPost && $originalPost->isComment()) {
            $originalPost = $originalPost->parent;
        }

        return redirect()->route('posts.show', $originalPost ?? $comment->reply_to)
            ->with('success', 'Comment updated successfully!');
    }

    /**
     * Soft delete the comment.
     *
     * @OA\Delete(
     *     path="/comments/{comment}",
     *     summary="Delete a comment",
     *     description="Soft delete a comment. Only comment owner can delete.",
     *     tags={"M08: Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         required=true,
     *         description="Comment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Comment deleted successfully"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function destroy(Content $comment)
    {
        // Ensure we're deleting a comment
        if (!$comment->isComment()) {
            abort(404, 'Comment not found.');
        }

        // Authorization - user can only delete their own comments
        Gate::authorize('delete', $comment);

        // Get the parent post before deletion (for redirect)
        $originalPost = $comment->parent;
        while ($originalPost && $originalPost->isComment()) {
            $originalPost = $originalPost->parent;
        }
        $postId = $originalPost ? $originalPost->id : $comment->reply_to;

        // Actually delete the comment (this will trigger update_comment_count)
        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        }

        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comment deleted successfully!');
    }
}
