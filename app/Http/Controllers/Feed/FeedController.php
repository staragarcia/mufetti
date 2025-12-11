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

        return view('pages.feed', compact('posts', 'user'));
    }

    /**
     * Show Personalized Feed
     */
    public function showPersonalizedFeed() //: View
    {
        // TODO
    }

}
