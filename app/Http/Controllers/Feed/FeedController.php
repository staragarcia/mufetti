<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;

use Illuminate\View\View;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    /**
     * Show Public Feed
     */
    public function showFeed() : View 
    {
        $user = Auth::user();
        
        $query = Content::posts()
            ->with('ownerUser')
            ->where('title', '!=', '[Deleted Post]')
            ->whereNull('id_group');

        // Filter out posts from private users unless authenticated user is following them
        if ($user) {
            $followingIds = $user->following()->pluck('id')->toArray();
            $query->where(function($q) use ($followingIds, $user) {
                // Show posts from public users
                $q->whereHas('ownerUser', function($userQuery) {
                    $userQuery->where('is_public', true);
                })
                // OR posts from private users that the auth user follows
                ->orWhereIn('owner', $followingIds)
                // OR the user's own posts
                ->orWhere('owner', $user->id);
            });
        } else {
            // For guests, only show posts from public users
            $query->whereHas('ownerUser', function($userQuery) {
                $userQuery->where('is_public', true);
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->get();

        return view('pages.feed', compact('posts', 'user'))->with('feedType', 'all');
    }

    /**
     * Show Personalized Feed (Following users only)
     */
    public function showPersonalizedFeed() : View
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get IDs of users that the current user is following
        $followingIds = $user->following()->pluck('id')->toArray();

        // Get posts from followed users (they're already approved followers)
        $posts = Content::posts()
            ->with('ownerUser')
            ->whereIn('owner', $followingIds)
            ->where('title', '!=', '[Deleted Post]')
            ->whereNull('id_group')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.feed', compact('posts', 'user'))->with('feedType', 'following');
    }

}
