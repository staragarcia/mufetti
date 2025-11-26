<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="M08: Profile",
 *     description="Endpoints to show authenticated user's profile and other users' profiles."
 * )
 */
class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     *
     * @OA\Get(
     *     path="/profile",
     *     summary="Show authenticated user's profile",
     *     description="Returns the profile view for the logged-in user including their posts.",
     *     tags={"M08: Profile"},
     *     @OA\Response(
     *         response=200,
     *         description="Profile view with user's posts"
     *     )
     * )
     */
    public function myProfile(): View
    {
        $user = Auth::user();

        $posts = Content::posts()
            ->where('owner', $user->id)
            ->where('title', '!=', '[Deleted Post]')
            ->orderBy('created_at', 'desc') // sorts posts by date, but if date is the same
            ->orderBy('id', 'desc') // then it sorts by id in descending order since newer posts have higher ids
            ->get();

        return view('pages.profile.profile', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    /**
     * Show a specific user's profile.
     *
     * @OA\Get(
     *     path="/users/{user}",
     *     summary="Show a user's profile",
     *     description="Displays the public profile and posts of another user if they are public.",
     *     tags={"M08: Profile"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User profile view"),
     *     @OA\Response(response=403, description="Profile is private and cannot be viewed")
     * )
     */
    public function show(User $user): View
    {
        $canView = $user->is_public;

        $posts = collect();
        if ($canView) {
            $posts = Content::posts()
                ->where('owner', $user->id)
                ->where('title', '!=', '[Deleted Post]')
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('pages.profile.show', compact('user', 'canView', 'posts'));
    }

}

