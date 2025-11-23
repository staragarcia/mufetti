<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    public function myProfile(): View
    {
        $user = Auth::user();
        
        $posts = Content::posts()
            ->where('owner', $user->id)
            ->where('title', '!=', '[Deleted Post]')
            ->orderBy('created_at', 'desc') // sorts posts by date, but if date is the same
            ->orderBy('id', 'desc') // then it sorts by id in descending order since newer posts have higher ids
            ->get();

        return view('pages.profile.profile', [
            'user' => $user,
            'posts' => $posts 
        ]);
    }

    public function show(User $user): View
    {
        $canView = $user->is_public;
        return view('pages.profile.show', compact('user', 'canView'));

    }

}
