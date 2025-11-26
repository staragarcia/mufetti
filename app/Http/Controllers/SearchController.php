<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="M09: Search",
 *     description="Endpoints to perform searches for posts, comments, groups, and users."
 * )
 */
class SearchController extends Controller
{
    /**
     * Show the search form.
     *
     * @OA\Get(
     *     path="/search",
     *     summary="Show search form",
     *     description="Displays the search page where users can enter a query.",
     *     tags={"M09: Search"},
     *     @OA\Response(
     *         response=200,
     *         description="Search form view"
     *     )
     * )
     */
    public function show()
    {
        return view('pages.search.search');
    }

    /**
     * Display search results.
     *
     * @OA\Get(
     *     path="/search/results",
     *     summary="Show search results",
     *     description="Performs search on posts, comments, groups, or users and returns results.",
     *     tags={"M09: Search"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         description="The search term",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="Type of resource to search: posts, comments, groups, users",
     *         @OA\Schema(type="string", default="posts")
     *     ),
     *     @OA\Response(response=200, description="Search results view"),
     *     @OA\Response(response=302, description="Redirect if query is empty")
     * )
     */
    public function results(Request $request)
    {
        $query = $request->query('query');
        $type  = $request->query('type', 'posts');

        if (!$query) {
            return redirect()->route('search.show')
                ->with('error', 'Please enter a search term.');
        }

        switch ($type) {
            case 'posts':
                $results = Content::posts()
                    ->where('title', 'ILIKE', "%{$query}%")
                    ->orWhere('description', 'ILIKE', "%{$query}%")
                    ->get();
                break;

            case 'comments':
                $results = Content::comments()
                    ->where('description', 'ILIKE', "%{$query}%")
                    ->get();
                break;

            case 'groups':
                $results = Group::where('name', 'ILIKE', "%{$query}%")
                    ->orWhere('description', 'ILIKE', "%{$query}%")
                    ->get();
                break;

            case 'users':
                $results = User::where('username', 'ILIKE', "%{$query}%")
                    ->orWhere('name', 'ILIKE', "%{$query}%")
                    ->get();
                break;

            default:
                $results = collect();
        }

        return view('pages.search.results', compact('results', 'query', 'type'));
    }
}

