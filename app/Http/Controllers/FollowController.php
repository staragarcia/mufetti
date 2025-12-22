<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationCreated;

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

        // If user has a private profile, create a follow request instead
        if (!$user->is_public) {
            // Check if there's already a pending request
            if ($currentUser->hasPendingRequestTo($user)) {
                return back()->with('info', 'You already have a pending request to this user.');
            }

            // Create follow request
            \App\Models\FollowRequest::create([
                'id_follower' => $currentUser->id,
                'id_followed' => $user->id,
                'status' => 'pending',
            ]);
            if ($user->owner !== $currentUser->id) {
                $notification = (object)[
                    'type' => 'followRequest',
                    'receiver' => $user->id,
                    'actor' => $currentUser->id,
                ];

                event(new NotificationCreated($notification));
            }

            return back()->with('success', 'Follow request sent to ' . $user->name);
        }

        // Add follow for public profiles
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
     * Show all users that a specific user is following
     */
    public function showFollowing(User $user)
    {
        $following = $user->following()->paginate(12);

        return view('pages.profile.following', compact('following', 'user'));
    }

    /**
     * Show all users that follow a specific user
     */
    public function showFollowers(User $user)
    {
        $followers = $user->followers()->paginate(12);

        return view('pages.profile.followers', compact('followers', 'user'));
    }

    public function removeFollower(User $follower)
    {
        $currentUser = Auth::user();
        $follower->following()->detach($currentUser->id);
        return back()->with('success', 'Removed ' . $follower->name . ' from your followers.');
    }
}
