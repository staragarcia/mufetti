<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    public function myProfile(): View
    {
        $user = Auth::user();

        return view('pages.profile.profile', [
            'user' => $user,
        ]);
    }

    public function show(User $user): View
    {
        $canView = $user->is_public;
        return view('pages.profile.show', compact('user', 'canView'));

    }

}
