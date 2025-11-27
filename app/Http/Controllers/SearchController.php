<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Show the search form.
     */
    public function show()
    {
        return view('pages.search.search');
    }

    /**
     * Display search results (AJAX).
     */
    public function results(Request $request)
    {
        $query = $request->query('query');
        $type  = $request->query('type', 'posts');

        if (!$query || strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'query' => $query,
                'type' => $type
            ]);
        }

        $results = [];

        switch ($type) {
            case 'posts':
                $results = Content::posts()
                    ->with('owner:id,username,name,profile_picture')
                    ->where(function($q) use ($query) {
                        $q->where('title', 'ILIKE', "%{$query}%")
                          ->orWhere('description', 'ILIKE', "%{$query}%");
                    })
                    ->select('id', 'title', 'description', 'img', 'owner', 'likes', 'comments', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($post) {
                        return [
                            'id' => $post->id,
                            'title' => $post->title,
                            'description' => $post->description,
                            'img' => $post->img,
                            'owner_username' => $post->owner->username ?? null,
                            'likes' => $post->likes,
                            'comments' => $post->comments,
                            'created_at' => $post->created_at
                        ];
                    });
                break;

            case 'comments':
                $results = Content::comments()
                    ->with('owner:id,username,name,profile_picture')
                    ->where('description', 'ILIKE', "%{$query}%")
                    ->select('id', 'description', 'owner', 'reply_to', 'likes', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($comment) {
                        return [
                            'id' => $comment->id,
                            'description' => $comment->description,
                            'owner_username' => $comment->owner->username ?? null,
                            'reply_to' => $comment->reply_to,
                            'likes' => $comment->likes,
                            'created_at' => $comment->created_at
                        ];
                    });
                break;

            case 'groups':
                $results = Group::with('ownerUser:id,username,name')
                    ->where(function($q) use ($query) {
                        $q->where('name', 'ILIKE', "%{$query}%")
                          ->orWhere('description', 'ILIKE', "%{$query}%");
                    })
                    ->select('id', 'name', 'description', 'owner', 'member_count', 'is_public')
                    ->orderBy('member_count', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($group) {
                        return [
                            'id' => $group->id,
                            'name' => $group->name,
                            'description' => $group->description,
                            'owner_username' => $group->ownerUser->username ?? null,
                            'member_count' => $group->member_count,
                            'is_public' => $group->is_public
                        ];
                    });
                break;

            case 'users':
                $results = User::where(function($q) use ($query) {
                        $q->where('username', 'ILIKE', "%{$query}%")
                          ->orWhere('name', 'ILIKE', "%{$query}%");
                    })
                    ->select('id', 'username', 'name', 'description', 'profile_picture', 'is_public')
                    ->limit(20)
                    ->get()
                    ->map(function($user) {
                        return [
                            'id' => $user->id,
                            'username' => $user->username,
                            'name' => $user->name,
                            'description' => $user->description,
                            'profile_picture' => $user->profile_picture,
                            'is_public' => $user->is_public
                        ];
                    });
                break;

            default:
                $results = collect();
        }

        return response()->json([
            'results' => $results,
            'query' => $query,
            'type' => $type
        ]);
    }
}
