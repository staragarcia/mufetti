<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        $currentUser = Auth::user();

        // Can't follow yourself
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Check if already following
        if ($currentUser->following()->where('id_following', $user->id)->exists()) {
            return back()->with('info', 'You are already following this user.');
        }

        // Add follow
        $currentUser->following()->attach($user->id);

        return back()->with('success', 'You are now following ' . $user->name);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        $currentUser = Auth::user();

        $currentUser->following()->detach($user->id);

        return back()->with('success', 'You unfollowed ' . $user->name);
    }

    /**
     * Get followers count
     */
    public function getFollowersCount(User $user)
    {
        return response()->json([
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ]);
    }

    /**
     * Show all users that the current user is following
     */
    public function showFollowing()
    {
        $user = Auth::user();
        $following = $user->following()->paginate(12);

        return view('pages.profile.following', compact('following', 'user'));
    }
}