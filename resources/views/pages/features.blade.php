@extends('layouts.app')

@section('title', 'Features')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-5xl">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-foreground mb-3"><span class="text-primary">mufetti</span> Features</h1>
        <p class="text-muted-foreground">Everything you need to connect with music lovers</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- User Management -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">User Profiles</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Create and customize your profile</li>
                <li>• Edit profile information and avatar</li>
                <li>• View other users' profiles</li>
                <li>• Delete your account</li>
            </ul>
        </div>

        <!-- Posts -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Posts</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Create posts with text and images</li>
                <li>• Edit and delete your posts</li>
                <li>• View post details and discussions</li>
                <li>• Share your favorite music</li>
            </ul>
        </div>

        <!-- Comments -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Comments & Replies</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Comment on posts</li>
                <li>• Reply to comments (one level)</li>
                <li>• Edit and delete your comments</li>
                <li>• Engage in discussions</li>
            </ul>
        </div>

        <!-- Reactions -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Reactions</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Like or dislike posts and comments</li>
                <li>• Change your reaction</li>
                <li>• View reaction counts</li>
                <li>• Express your opinion</li>
            </ul>
        </div>

        <!-- Groups -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Music Groups</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Create and manage groups</li>
                <li>• Join public or private groups</li>
                <li>• Request to join private groups</li>
                <li>• Manage group members</li>
            </ul>
        </div>

        <!-- Follow System -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Follow System</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Follow other users</li>
                <li>• View followers and following lists</li>
                <li>• Unfollow users</li>
                <li>• Build your network</li>
            </ul>
        </div>

        <!-- Feed -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Personalized Feed</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• View posts from followed users</li>
                <li>• See posts from your groups</li>
                <li>• Discover new content</li>
                <li>• Stay updated with your network</li>
            </ul>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Search</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Search for users</li>
                <li>• Find groups</li>
                <li>• Discover posts</li>
                <li>• Advanced search filters</li>
            </ul>
        </div>

        <!-- Admin Panel -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Admin Panel</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• User management</li>
                <li>• Content moderation</li>
                <li>• Platform administration</li>
                <li>• System oversight</li>
            </ul>
        </div>

        <!-- Authentication -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Authentication</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Secure user registration</li>
                <li>• Login and logout</li>
                <li>• Password recovery</li>
                <li>• Session management</li>
            </ul>
        </div>

        <!-- Import Albums -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4v8" />
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Import Albums</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Import albums from MusicBrainz</li>
                <li>• Automatic cover art and tracklist</li>
                <li>• Search by album</li>
            </ul>
        </div>

        <!-- Albums, Reviews & Favorites -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polygon points="12 2 15 9 22 9.5 17 14.5 18.5 22 12 18.5 5.5 22 7 14.5 2 9.5 9 9" />
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Albums, Reviews & Favorites</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Write and rate album reviews</li>
                <li>• Mark albums as favorites and show them on your profile</li>
                <li>• See reviews and favorites on album and user pages</li>
            </ul>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Notifications</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Real-time notifications for follows, comments, and more</li>
                <li>• Mark notifications as read</li>
            </ul>
        </div>

        <!-- Reports & Moderation -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="6" x2="12" y2="14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="18" r="0.4" fill="currentColor"/>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Reports & Moderation</h2>
            </div>
            <ul class="text-muted-foreground space-y-2 ml-4">
                <li>• Report inappropriate content</li>
                <li>• Admin review and moderation tools</li>
            </ul>
        </div>

    </div>

</div>
@endsection