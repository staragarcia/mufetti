@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="grid grid-cols-12 gap-4">
        {{-- LEFT COLUMN: Profile Card --}}
        <div class="col-span-12 lg:col-span-3 md:col-span-4">
            {{-- Profile Card Container --}}
            @if($user)
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm transition-all duration-200 hover:shadow-xl hover:-translate-y-1 group">
                
                {{-- Clickable Upper Part (Header + Content) --}}
                <a href="{{ route('pages.profile.show') }}" class="block cursor-pointer">
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 mb-4">

                        <img
                            src="{{ $user->profile_picture ?? '/placeholder.jpg' }}"
                            alt="Avatar"
                            class="h-32 w-32 rounded-full border-4 border-background object-cover bg-muted"
                        />

                        <div class="flex-1 min-w-0">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                <div>
                                    <h1 class="text-2xl sm:text-3xl font-bold text-foreground">
                                        {{ $user->name }}
                                    </h1>
                                    <p class="text-muted-foreground">{{ "@" . $user->username }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </a>
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

        {{-- RIGHT COLUMN: Empty --}}
        <div class="col-span-12 lg:col-span-3 hidden lg:block">
            <!-- Empty space for future content -->
        </div>
    </div>
</div>
@endsection
