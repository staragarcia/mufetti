<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;

use Illuminate\View\View;
use App\Models\Content;
use App\Models\Album;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Get suggested albums
        $suggestedAlbums = $this->getSuggestedAlbums($user);
        
        // Get suggested users to follow
        $suggestedUsers = $this->getSuggestedUsers($user);

        return view('pages.feed', compact('posts', 'user', 'suggestedAlbums', 'suggestedUsers'))->with('feedType', 'all');
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

        // Get suggested albums
        $suggestedAlbums = $this->getSuggestedAlbums($user);
        
        // Get suggested users to follow
        $suggestedUsers = $this->getSuggestedUsers($user);

        return view('pages.feed', compact('posts', 'user', 'suggestedAlbums', 'suggestedUsers'))->with('feedType', 'following');
    }

    /**
     * Get hot/popular albums based on most reviews
     */
    private function getSuggestedAlbums($user)
    {
        // Always show hot albums based on review count
        return Album::with('artists')
            ->orderBy('reviews_total', 'desc')
            ->orderBy('avg_rating', 'desc')
            ->limit(3)
            ->get();
    }

    /**
     * Get suggested users to follow
     */
    private function getSuggestedUsers($user)
    {
        if (!$user) {
            // For guests, show popular public users
            return User::where('is_public', true)
                ->where('id', '!=', User::ANONYMOUS_ID)
                ->withCount('followers')
                ->orderBy('followers_count', 'desc')
                ->limit(5)
                ->get();
        }

        // Get IDs of users already following
        $followingIds = $user->following()->pluck('id')->toArray();
        $followingIds[] = $user->id; // Exclude self

        // Get pending follow requests
        $pendingRequestIds = $user->followRequestsSent()
            ->where('status', 'pending')
            ->pluck('id_followed')
            ->toArray();

        // Strategy 1: Find users followed by people you follow (friends of friends)
        $friendsOfFriends = User::whereIn('id', function($query) use ($followingIds) {
                $query->select('id_following')
                    ->from('followings')
                    ->whereIn('id_user', $followingIds);
            })
            ->whereNotIn('id', array_merge($followingIds, $pendingRequestIds))
            ->where('id', '!=', User::ANONYMOUS_ID)
            ->withCount('followers')
            ->limit(3)
            ->get();

        // Strategy 2: Find users with similar album taste
        $favoriteAlbumIds = $user->favouriteAlbums()->pluck('id_album')->toArray();
        
        $similarTasteUsers = collect();
        if (!empty($favoriteAlbumIds)) {
            $similarTasteUsers = User::whereIn('id', function($query) use ($favoriteAlbumIds) {
                    $query->select('id_user')
                        ->from('favourite_albums')
                        ->whereIn('id_album', $favoriteAlbumIds)
                        ->groupBy('id_user')
                        ->havingRaw('COUNT(*) >= 2'); // At least 2 albums in common
                })->whereNotIn('id', array_merge($followingIds, $pendingRequestIds))
                ->where('id', '!=', User::ANONYMOUS_ID)
                ->withCount('followers')
                ->limit(2)
                ->get();
        }

        // Merge suggestions
        $suggestedUsers = $friendsOfFriends->merge($similarTasteUsers)->unique('id');

        // If not enough, add popular users
        if ($suggestedUsers->count() < 5) {
            $popularUsers = User::where('is_public', true)
                ->whereNotIn('id', array_merge(
                    $followingIds, 
                    $pendingRequestIds,
                    $suggestedUsers->pluck('id')->toArray()
                ))
                ->where('id', '!=', User::ANONYMOUS_ID)
                ->withCount('followers')
                ->orderBy('followers_count', 'desc')
                ->limit(5 - $suggestedUsers->count())
                ->get();
            
            $suggestedUsers = $suggestedUsers->merge($popularUsers);
        }

        return $suggestedUsers->take(5);
    }

}
