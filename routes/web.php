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
use App\Http\Controllers\Feed\FeedController;
use App\Http\Controllers\DeleteAccountController;


// HOME
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('pages.profile.show')
        : redirect()->route('login');
})->name('home');


// SEARCH
Route::get('/search', [SearchController::class, 'show'])->name('search.show');
Route::get('/search/results', [SearchController::class, 'results'])->name('search.results');


// ⚠ PERFIL DE OUTRO USER (corrigido)
Route::get('/users/{user}', [ProfileController::class, 'show'])
    ->name('profile.showOther');


// PUBLIC FEED
Route::get('/feed', [FeedController::class, 'showFeed'])->name('feed.show');


// ----------------------- GUEST -----------------------
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
        ->name('password.request');
});


// ----------------------- AUTH -----------------------
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


    // ⚡ PERFIL DO PRÓPRIO USER (corrigido)
    Route::get('/profile', [ProfileController::class, 'myProfile'])
        ->name('pages.profile.show');

    // Edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('pages.profile.edit');

    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Privacy toggle
    Route::post('/profile/privacy', [ProfileController::class, 'togglePrivacy'])
        ->name('profile.togglePrivacy');

    // Delete profile
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])
        ->name('profile.delete');

    Route::delete('/account/delete', [DeleteAccountController::class, 'destroy'])
        ->name('account.delete');


    // POSTS
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // REACTIONS
    Route::post('/posts/{post}/react', [ReactionController::class, 'toggle'])->name('posts.react');
    Route::get('/posts/{post}/reactions', [ReactionController::class, 'getCounts'])->name('posts.reactions.counts');


    // GROUPS
    Route::get('/groups', [GroupController::class, 'showUserGroups'])->name('groups.showUserGroups');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/groups/{group}/join', [GroupController::class, 'joinRequest'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::post('/groups/{group}/update', [GroupController::class, 'update'])->name('groups.update');
    Route::post('/groups/{group}/join/public', [GroupController::class, 'joinPublicGroup'])->name('groups.join.public');

    // GROUP JOIN REQUESTS
    Route::post('/join-requests/{request}/accept', [GroupController::class, 'acceptJoinRequest'])->name('joinRequests.accept');
    Route::post('/join-requests/{request}/decline', [GroupController::class, 'declineJoinRequest'])->name('joinRequests.decline');
    Route::get('/groups/{group}/requests', [GroupController::class, 'showJoinRequests'])->name('groups.requests');
});


// GROUP PUBLIC PAGE
Route::get('/groups/{group}', [GroupController::class, 'showGroup'])->name('groups.show');
