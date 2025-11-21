<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;

// =======================
//      HOME
// =======================
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('profile.show')
        : redirect()->route('login');
})->name('home');


// =======================
//  AUTH (APENAS GUEST)
// =======================
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);


    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('password.request');

});


// =======================
//    AUTHENTICATED
// =======================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::post('/profile/privacy', [ProfileController::class, 'togglePrivacy'])
        ->name('profile.togglePrivacy');

    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])
        ->name('profile.delete');
});
