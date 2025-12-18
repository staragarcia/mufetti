<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Exceptions\ExceptionHandler;
use Illuminate\Support\Str;
use App\Models\User;


class GoogleController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle() {

        $google_user = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $google_user->getId())->first();
        
        // If the user does not exist, create one
        if (!$user) {

            // create a silly username
            $username = Str::snake($google_user->getName());
            $username = $username . (string)rand(2,999);

            // Store the provided name, email, and Google ID in the database
            $new_user = User::create([
                'name' => $google_user->getName(),
                'username' => $username,
                'email' => $google_user->getEmail(),
                'google_id' => $google_user->getId(),
                'birth_date'  => '1918-10-18',
                'password' => 'useless_password',
                'description' => "A new music lover!",
                'is_public'   => true,
                'is_admin'    => false,
            ]);

            Auth::login($new_user);

        // Otherwise, simply log in with the existing user
        } else {
            Auth::login($user);
        }

        // After login, redirect to homepage. in our case the homepage is the user profile
        return redirect()->intended('profile');
    }

}
