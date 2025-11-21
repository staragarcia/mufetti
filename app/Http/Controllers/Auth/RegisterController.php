<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Show registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:255|unique:users,username',
            'email'       => 'required|email|max:255|unique:users,email',
            'birth_date'  => 'required|date',
            'password'    => 'required|min:6|confirmed',
            'description' => 'nullable|string',
            'is_public'   => 'nullable|boolean',
        ]);

        // Create user
        $user = User::create([
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => $request->password,  // hashed automatically!
            'birth_date'  => $request->birth_date,
            'description' => $request->description,
            'is_public'   => $request->is_private ? false : true,
            'is_admin'    => false,
        ]);

        // Login the new user
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('profile.show')
            ->with('success', 'Account created successfully!');
    }
}
