@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="grid grid-cols-12 gap-4">
        {{-- LEFT COLUMN: Profile Card --}}
        <div class="col-span-12 lg:col-span-3 md:col-span-4">
            @if($user)
                {{-- Profile Card Container --}}
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                    {{-- Background Header --}}
                    <div class="h-20 bg-gray-100"></div>
                    
                    {{-- Profile Content --}}
                    <div class="px-4 pb-4 -mt-12">
                        <a href="{{ route('pages.profile.show') }}" class="block">
                            {{-- Avatar --}}
                            <div class="mb-3">
                                <img
                                    src="{{ $user->avatar }}"
                                    alt="Avatar"
                                    class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg hover:scale-105 transition-transform duration-200"
                                />
                            </div>

                            {{-- User Info --}}
                            <div class="space-y-1">
                                <h2 class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                                    {{ $user->name }}
                                </h2>
                                <p class="text-sm text-gray-500">{{ "@" . $user->username }}</p>
                            </div>
                        </a>
                        
                        {{-- Stats --}}
                        @if($user->description)
                        <p class="mt-3 text-sm text-gray-600 line-clamp-3">{{ $user->description }}</p>
                        @endif
                        
                        <div class="mt-4 flex gap-4 text-sm">
                            <a href="{{ route('followers.show', $user) }}" class="hover:underline">
                                <span class="font-semibold text-gray-900">{{ $user->followers()->count() }}</span>
                                <span class="text-gray-500">Followers</span>
                            </a>
                            <a href="{{ route('following.show', $user) }}" class="hover:underline">
                                <span class="font-semibold text-gray-900">{{ $user->following()->count() }}</span>
                                <span class="text-gray-500">Following</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- Welcome Card for Non-Logged-In Users --}}
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    {{-- Background Header --}}
                    <div class="h-20 bg-gray-100"></div>
                    
                    {{-- Welcome Content --}}
                    <div class="px-4 pb-4 -mt-12">
                        <div class="mb-3">
                            <div class="h-24 w-24 rounded-full border-4 border-white bg-white shadow-lg flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <h2 class="text-xl font-bold text-gray-900">
                                Welcome to Mufetti
                            </h2>
                            <p class="text-sm text-gray-500">Join the community</p>
                        </div>
                        
                        <p class="mt-3 text-sm text-gray-600">
                            Share your music passion, discover albums, and connect with music lovers.
                        </p>
                        
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('register') }}" 
                               class="block w-full px-4 py-2 bg-blue-600 text-white text-center text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Sign Up
                            </a>
                            <a href="{{ route('login') }}" 
                               class="block w-full px-4 py-2 text-center text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                Already have an account?
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- MIDDLE COLUMN: Posts --}}
        <div class="col-span-12 lg:col-span-6 md:col-span-8">
            
            {{-- Feed Type Tabs (only for logged in users) --}}
            @if($user)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6 overflow-hidden">
                <div class="flex">
                    {{-- All Posts Tab --}}
                    <a href="{{ route('feed.show') }}" 
                       class="flex-1 px-6 py-3 text-center font-medium transition-all duration-200 border-b-2 
                              {{ ($feedType ?? 'all') === 'all' 
                                  ? 'border-blue-500 text-blue-600 bg-blue-50' 
                                  : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>All Posts</span>
                        </div>
                    </a>

                    {{-- Following Tab --}}
                    <a href="{{ route('feed.following') }}" 
                       class="flex-1 px-6 py-3 text-center font-medium transition-all duration-200 border-b-2 
                              {{ ($feedType ?? 'all') === 'following' 
                                  ? 'border-blue-500 text-blue-600 bg-blue-50' 
                                  : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Following</span>
                        </div>
                    </a>
                </div>
            </div>
            @endif

            <div class="space-y-6">
                @forelse($posts as $post)
                    {{-- Post Card Container --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm transition-all duration-200 hover:shadow-xl hover:-translate-y-1 group">
                        
                        {{-- Clickable Upper Part (Header + Content) --}}
                        <a href="{{ route('posts.show', $post) }}" class="block cursor-pointer">
                            {{-- Post Header --}}
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $post->title }}
                                </h3>
                                <span class="text-sm text-gray-500">
                                    by {{ $post->ownerUser->username }} - {{ $post->created_at->format('M j, Y') }}
                                </span>
                            </div>

                            {{-- Post Content --}}
                            <div class="flex gap-4">
                                
                                {{-- Image --}}
                                @if($post->img)
                                <div class="flex-shrink-0 w-48 overflow-hidden rounded-md">
                                    <img 
                                        src="{{ asset('storage/' . $post->img) }}" 
                                        alt="Post image" 
                                        class="w-full h-48 object-cover transition-all duration-200 group-hover:scale-105"
                                    >
                                </div>
                                @endif
                                
                                {{-- Text with Conditional Fade --}}
                                <div class="flex-1 min-w-0"> 
                                    <div class="max-h-48 overflow-hidden relative"> 
                                        <p class="text-gray-600 whitespace-pre-line leading-relaxed" id="text-{{ $post->id }}">{{ trim($post->description ?? '') }}</p>
                                        {{-- Fade Overlay with "Continue reading"--}}
                                        <div class="fade-overlay absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none hidden" id="fade-{{ $post->id }}">
                                            <div class="h-full flex items-end justify-center pb-2">
                                                <div class="flex items-center gap-1 text-blue-600 font-semibold text-sm">
                                                    <span>Continue reading</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        {{-- Footer with Reaction Buttons (NOT clickable for post navigation) --}}
                        <div class="flex justify-between items-center text-sm text-gray-500 mt-4 pt-4 border-t border-gray-200">
                            <div class="flex gap-4">
                                {{-- Like Button --}}
                                <button class="reaction-btn flex items-center gap-1 hover:text-blue-600 transition-colors" 
                                        data-post-id="{{ $post->id }}" 
                                        data-type="like"
                                        data-likes-count="{{ $post->likes }}">
                                    <svg class="w-5 h-5 like-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="likes-count">{{ $post->likes }}</span>
                                </button>

                                {{-- Confetti Button --}}
                                <button class="reaction-btn flex items-center gap-1 hover:text-yellow-600 transition-colors" 
                                        data-post-id="{{ $post->id }}" 
                                        data-type="confetti"
                                        data-confetti-count="{{ $post->comments }}">
                                    <svg class="w-5 h-5 confetti-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2v4M12 22v-4M2 12h4M22 12h-4"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5l3 3M19 5l-3 3M5 19l3-3M19 19l-3-3"/>
                                    </svg>
                                    <span class="confetti-count">{{ $post->comments }}</span>
                                </button>

                                {{-- Comment Button --}}
                                <div class="flex items-center gap-1 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span>{{ $post->comments ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-12 shadow-sm text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        @if(($feedType ?? 'all') === 'following')
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Posts from Following</h3>
                            <p class="text-gray-600 mb-4">
                                You're not following anyone yet, or the people you follow haven't posted anything.
                            </p>
                            <a href="{{ route('search.show') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Find People to Follow
                            </a>
                        @else
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Posts Yet</h3>
                            <p class="text-gray-600">Be the first to share something!</p>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: Suggestions --}}
        <div class="col-span-12 lg:col-span-3 hidden lg:block">
            <div class="sticky top-4 space-y-4 max-h-[calc(100vh-2rem)] overflow-y-auto">
                
                {{-- Suggested Albums --}}
                @if(isset($suggestedAlbums) && $suggestedAlbums->count() > 0)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                            </svg>
                            Hot Albums
                        </h3>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @foreach($suggestedAlbums as $album)
                        <a href="{{ route('albums.show', $album) }}" 
                           class="block p-3 hover:bg-gray-50 transition-colors duration-150 group">
                            <div class="flex gap-3">
                                {{-- Album Cover --}}
                                <div class="flex-shrink-0">
                                    @if($album->cover_url)
                                        <img src="{{ $album->cover_url }}" 
                                             alt="{{ $album->title }}"
                                             class="w-12 h-12 rounded object-cover group-hover:opacity-90 transition-opacity">
                                    @else
                                        <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Album Info --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                                        {{ $album->title }}
                                    </h4>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $album->artists->pluck('name')->join(', ') }}
                                    </p>
                                    @if($album->avg_rating)
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                        <span class="text-xs text-gray-600 font-medium">{{ number_format($album->avg_rating, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    
                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
                        <a href="{{ route('albums.index') }}" 
                           class="text-xs text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center gap-1">
                            Show more
                        </a>
                    </div>
                </div>
                @endif

                {{-- Suggested Users --}}
                @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Who to Follow
                        </h3>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @foreach($suggestedUsers as $suggestedUser)
                        <div class="p-3 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center gap-3">
                                {{-- User Avatar --}}
                                <a href="{{ route('pages.profile.show', $suggestedUser->id) }}" class="flex-shrink-0">
                                    <img src="{{ $suggestedUser->avatar }}" 
                                         alt="{{ $suggestedUser->name }}"
                                         class="w-10 h-10 rounded-full object-cover hover:opacity-90 transition-opacity">
                                </a>
                                
                                {{-- User Info --}}
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('pages.profile.show', $suggestedUser->id) }}" 
                                       class="block">
                                        <h4 class="text-sm font-medium text-gray-900 hover:text-purple-600 transition-colors truncate">
                                            {{ $suggestedUser->name }}
                                        </h4>
                                        <p class="text-xs text-gray-500 truncate">{{ '@' . $suggestedUser->username }}</p>
                                    </a>
                                </div>
                                
                                {{-- Follow Button --}}
                                @if($user && $user->id !== $suggestedUser->id)
                                <div class="flex-shrink-0">
                                    @if($user->isFollowing($suggestedUser))
                                        <button disabled
                                                class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-500 rounded-full cursor-not-allowed">
                                            Following
                                        </button>
                                    @elseif($user->hasPendingRequestTo($suggestedUser))
                                        <button disabled
                                                class="px-3 py-1 text-xs font-medium bg-yellow-50 text-yellow-600 rounded-full cursor-not-allowed">
                                            Pending
                                        </button>
                                    @else
                                        <form action="{{ route('users.follow', $suggestedUser) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 text-xs font-medium bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                                Follow
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
                        <a href="{{ route('search.show') }}" 
                           class="text-xs text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center gap-1">
                            Show more
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
