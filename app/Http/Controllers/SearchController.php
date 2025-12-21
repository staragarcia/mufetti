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
                    ->fullTextSearch($query)
                    ->with('ownerUser:id,username,name,profile_picture')
                    ->limit(20)
                    ->get()
                    ->map(function($post) {
                        return [
                            'id' => $post->id,
                            'title' => $post->title,
                            'description' => $post->description,
                            'img' => $post->img,
                            'owner_username' => $post->ownerUser->username ?? null,
                            'likes' => $post->likes,
                            'comments' => $post->comments,
                            'created_at' => $post->created_at,
                            'relevance' => round($post->rank ?? 0,4)
                        ];
                    });
                break;

            case 'comments':
                $results = Content::comments()
                    ->fullTextSearch($query)
                    ->with('ownerUser:id,username,name,profile_picture')
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($comment) {
                        return [
                            'id' => $comment->id,
                            'description' => $comment->description,
                            'owner_username' => $comment->ownerUser->username ?? null,
                            'reply_to' => $comment->reply_to,
                            'likes' => $comment->likes,
                            'created_at' => $comment->created_at,
                            'relevance' => round($comment->rank ?? 0, 4)
                        ];
                    });
                break;

            case 'groups':
                $results = Group::fullTextSearch($query)
                    ->with('ownerUser:id,username,name')
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
                            'is_public' => $group->is_public,
                            'relevance' => round($group->rank ?? 0, 4)
                        ];
                    });
                break;

            case 'users':
                $results = User::fullTextSearch($query)
                    // ->select('id', 'username', 'name', 'description', 'profile_picture', 'is_public')
                    ->limit(20)
                    ->get()
                    ->map(function($user) {
                        return [
                            'id' => $user->id,
                            'username' => $user->username,
                            'name' => $user->name,
                            'description' => $user->description,
                            'profile_picture' => $user->profile_picture,
                            'is_public' => $user->is_public,
                            'relevance' => round($user->rank ?? 0, 4)
                        ];
                    });
                break;

            default:
                $results = collect();
        }

        return response()->json([
            'results' => $results,
            'query' => $query,
            'type' => $type,
            'count' => $results->count()
        ]);
    }
}
