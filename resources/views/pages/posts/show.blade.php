@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-12">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>

        {{-- Post Card --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            
            {{-- Post Header --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                        {{ $post->title }}
                    </h1>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="font-medium">{{ $post->ownerUser->name }}</span>
                        <span>•</span>
                        <span>{{ $post->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
                
                {{-- Post actions dropdown --}}
                @if(auth()->id() === $post->ownerUser->id)
                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded hover:bg-gray-100"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                    
                    {{-- Dropdown menu --}}
                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                        x-cloak
                    >
                        <div class="py-1">
                            {{-- Edit option --}}
                            <a 
                                href="{{ route('posts.edit', $post) }}" 
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Post
                            </a>
                            
                            {{-- Delete option --}}
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="hover:bg-gray-100">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:text-red-800 transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this post?')"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Post
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Post Image --}}
            @if($post->img)
            <div class="mb-6 flex justify-center">
                <div class="w-full max-w-2xl">
                    <img 
                        src="{{ $post->img }}" 
                        alt="Post image" 
                        class="w-full h-auto max-h-[400px] min-h-[200px] object-contain rounded-lg bg-white" 
                        onerror="this.style.display='none'"
                    >
                </div>
            </div>
            @endif

            {{-- Post Content --}}
            <div class="prose prose-lg max-w-none mb-8">
                <p class="text-gray-700 whitespace-pre-line leading-relaxed text-lg">
                    {{ $post->description }}
                </p>
            </div>

            {{-- Footer with Reaction Buttons --}}
            <div class="flex justify-between items-center text-sm text-gray-500 pt-6 border-t border-gray-200">
                <div class="flex gap-6">
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
                        <span>0</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Comments Section (tba) --}}
        <div class="mt-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Comments</h3>
                <p class="text-gray-500 text-center py-8">Comments feature coming soon!</p>
            </div>
        </div>

    </main>
</div>

{{-- Add Alpine.js for dropdown functionality --}}
<script src="//unpkg.com/alpinejs" defer></script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection