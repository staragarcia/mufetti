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
        $posts = Content::posts()
            ->with('ownerUser')
            ->where('title', '!=', '[Deleted Post]')
            ->whereNull('id_group')
            ->orderBy('created_at', 'desc')
            ->get();

        $user = Auth::user();

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

        // Get posts from followed users
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
