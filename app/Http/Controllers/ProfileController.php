<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        $posts = Content::posts()
            ->where('owner', $user->id)
            ->where('title', '!=', '[Deleted Post]')
            ->orderBy('created_at', 'desc') // sorts posts by date, but if date is the same
            ->orderBy('id', 'desc') // then it sorts by id in descending order since newer posts have higher ids
            ->get();

        return view('pages.profile', [
            'user' => $user,
            'posts' => $posts 
        ]);
    }
}
