<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SearchController;


Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('profile.show')
        : redirect()->route('login');
})->name('home');


//search
Route::get('/search', [SearchController::class, 'show'])->name('search.show');
Route::get('/search/results', [SearchController::class, 'results'])->name('search.results');

// View another user's profile
Route::get('/profile/{user:id}', [ProfileController::class, 'show'])->name('profile.showOther');


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
    Route::get('/profile', [ProfileController::class, 'myProfile'])
        ->name('profile.show');

    Route::post('/profile/privacy', [ProfileController::class, 'togglePrivacy'])
        ->name('profile.togglePrivacy');

        Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])
        ->name('profile.delete');

    // posts
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show'); 
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // reactions
    Route::post('/posts/{post}/react', [ReactionController::class, 'toggle'])->name('posts.react');
    Route::get('/posts/{post}/reactions', [ReactionController::class, 'getCounts'])->name('posts.reactions.counts');
    
    //groups
    Route::get('/groups', [GroupController::class, 'showUserGroups'])->name('groups.showUserGroups');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'showGroup'])->name('groups.show');


});
