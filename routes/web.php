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
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\FollowRequestController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AlbumReviewController;


// -----------------------------------------------------
// HOME
// -----------------------------------------------------
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('pages.profile.show')
        : redirect()->route('login');
})->name('home');


// -----------------------------------------------------
// ABOUT, CONTACT & MAIN FEATURES
// -----------------------------------------------------
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/features', function () {
    return view('pages.features');
})->name('features');


// -----------------------------------------------------
// SEARCH
// -----------------------------------------------------
Route::get('/search', [SearchController::class, 'show'])->name('search.show');
Route::get('/search/results', [SearchController::class, 'results'])->name('search.results');


// -----------------------------------------------------
// PUBLIC FEED
// -----------------------------------------------------
Route::get('/feed', [FeedController::class, 'showFeed'])->name('feed.show');

// Personalized Feed (Following only) - Auth required
Route::middleware('auth')->group(function () {
    Route::get('/feed/following', [FeedController::class, 'showPersonalizedFeed'])->name('feed.following');
});



// -----------------------------------------------------
// GUEST ROUTES
// -----------------------------------------------------
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

    // google auth
    Route::controller(GoogleController::class)->group(function () {
        Route::get('auth/google', 'redirect')->name('google-auth');
        Route::get('auth/google/call-back', 'callbackGoogle')->name('google-call-back');
    });

});


// -----------------------------------------------------
// AUTH ROUTES
// -----------------------------------------------------
Route::middleware('auth')->group(function () {

    // -------------------------------------------------
    // LOGOUT
    // -------------------------------------------------
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


    // -------------------------------------------------
    // PROFILE (OWN)
    // -------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'myProfile'])
        ->name('pages.profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('pages.profile.edit');

    Route::post('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile/picture', [ProfileController::class, 'removeProfilePicture'])
        ->name('profile.removePicture');

    Route::post('/profile/privacy', [ProfileController::class, 'togglePrivacy'])
        ->name('profile.togglePrivacy');

    Route::delete('/profile/{id}', [DeleteAccountController::class, 'destroy'])
        ->name('profile.delete');


    // -------------------------------------------------
    // PROFILE (OTHER USERS)
    // -------------------------------------------------
    Route::get('/users/{user}', [ProfileController::class, 'show'])
        ->name('profile.showOther');


    // -------------------------------------------------
    // FOLLOWERS
    // -------------------------------------------------
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::post('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');
    Route::post('/users/{follower}/remove-follower', [FollowController::class, 'removeFollower'])->name('users.removeFollower');
    Route::get('/users/{user}/followers', [FollowController::class, 'showFollowers'])->name('followers.show');
    Route::get('/users/{user}/following', [FollowController::class, 'showFollowing'])->name('following.show');
    Route::get('/users/{user}/followers/count', [FollowController::class, 'getFollowersCount'])->name('users.followers.count');

    // -------------------------------------------------
    // FOLLOW REQUESTS
    // -------------------------------------------------
    Route::get('/profile/requests', [FollowRequestController::class, 'index'])->name('followRequests.index');
    Route::post('/follow-requests/{request}/accept', [FollowRequestController::class, 'accept'])->name('followRequests.accept');
    Route::post('/follow-requests/{request}/decline', [FollowRequestController::class, 'decline'])->name('followRequests.decline');


    // -------------------------------------------------
    // POSTS
    // -------------------------------------------------
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');


    // -------------------------------------------------
    // COMMENTS
    // -------------------------------------------------
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


    // -------------------------------------------------
    // REACTIONS
    // -------------------------------------------------
    Route::post('/posts/{post}/react', [ReactionController::class, 'toggle'])->name('posts.react');
    Route::get('/posts/{post}/reactions', [ReactionController::class, 'getCounts'])->name('posts.reactions.counts');
    Route::post('/comments/{comment}/react', [ReactionController::class, 'toggleComment'])->name('comments.react');
    Route::get('/comments/{comment}/reactions', [ReactionController::class, 'getCommentCounts'])->name('comments.reactions.counts');


    // -------------------------------------------------
    // GROUPS
    // -------------------------------------------------
    Route::get('/groups', [GroupController::class, 'showUserGroups'])->name('groups.showUserGroups');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/groups/{group}/join', [GroupController::class, 'joinRequest'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');
    Route::get('/groups/{group}/members', [GroupController::class, 'showMembers'])->name('groups.members');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::post('/groups/{group}/update', [GroupController::class, 'update'])->name('groups.update');
    Route::post('/groups/{group}/join/public', [GroupController::class, 'joinPublicGroup'])->name('groups.join.public');
    Route::get('/groups/{group}/posts/create', [PostController::class, 'create'])->name('posts.create.withGroup');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::put('/groups/{group}/transfer-owner/{user}', [GroupController::class, 'transferOwner'])->name('groups.transferOwner');

    // Join Requests
    Route::post('/join-requests/{request}/accept', [GroupController::class, 'acceptJoinRequest'])->name('joinRequests.accept');
    Route::post('/join-requests/{request}/decline', [GroupController::class, 'declineJoinRequest'])->name('joinRequests.decline');
    Route::get('/groups/{group}/requests', [GroupController::class, 'showJoinRequests'])->name('groups.requests');

    // feed


    // Admin - simple user management panel (search, view, edit, create, delete)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/users/{user}/picture', [AdminUserController::class, 'removeProfilePicture'])->name('users.removePicture');
    });


    //album
    Route::get('/albums/search', [AlbumController::class, 'search'])->name('albums.search');
    Route::post('/albums/import', [AlbumController::class, 'import'])->name('albums.import');
    Route::get('/albums/import', [AlbumController::class, 'showImportForm'])->name('albums.import.form');
    Route::get('/albums/import/search', [AlbumController::class, 'searchImport'])->name('albums.import.search');
    Route::get('/albums/{album}', [AlbumController::class, 'show'])->name('albums.show');
    //albumReview
    Route::post('/albums/{album}/reviews', [AlbumReviewController::class, 'store'])->name('album-reviews.store');
    Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
});


// -----------------------------------------------------
// PUBLIC GROUP PAGE
// -----------------------------------------------------
Route::get('/groups/{group}', [GroupController::class, 'showGroup'])->name('groups.show');

Route::get('feed', [FeedController::class, 'showFeed'])->name('feed.show');
