<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DeleteAccountController;



Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('pages.profile.show')
        : redirect()->route('login');
})->name('home');



Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);


    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm']) ->name('password.request');

});



Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('pages.profile.show');

    Route::post('/profile/privacy', [ProfileController::class, 'togglePrivacy'])
        ->name('profile.togglePrivacy');
    
        Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('pages.profile.edit');

    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])
        ->name('profile.delete');

     Route::delete('/account/delete', [DeleteAccountController::class, 'destroy'])
        ->middleware('auth')
        ->name('account.delete');
    
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->middleware('auth')
        ->name('pages.profile.edit');
    
    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->middleware('auth')
        ->name('profile.update');
    
    

    // posts
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
});
