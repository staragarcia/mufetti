<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;

use Illuminate\View\View;
use App\Models\Content;


class FeedController extends Controller
{
    /**
     * Show Public Feed
     */
    public function showFeed() : View 
    {
        $posts = Content::posts()->with('ownerUser')->orderBy('created_at', 'desc')->get();

        return view('pages.feed', compact('posts'));
    }

}
