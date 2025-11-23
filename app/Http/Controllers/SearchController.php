<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show()
    {
        return view('pages.search.search');
    }

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

