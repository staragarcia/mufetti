@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">

        <div class="relative h-48 sm:h-64 rounded-xl overflow-hidden mb-4 bg-muted">
            @if($user->profile_picture)
                <img src="{{ $user->profile_picture }}" class="w-full h-full object-cover" />
            @else
                <div class="w-full h-full bg-muted"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent"></div>
        </div>

        <div class="relative px-4 sm:px-6">

            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 -mt-16 sm:-mt-20 mb-6">

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

            <div class="space-y-4 mb-6">

                <p class="text-foreground leading-relaxed">
                    {{ $user->description ?? 'No bio yet.' }}
                </p>

                <div class="flex flex-wrap gap-4 text-sm text-muted-foreground">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>

                        <span>Joined {{ $user->created_at?->format('F Y') ?? '2024' }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        @if($user->is_public)
                            <span class="text-green-600 font-semibold">Public Profile</span>
                        @else
                            <span class="text-red-600 font-semibold">Private Profile</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-6 text-sm">
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">{{ $postsCount ?? ($posts->count() ?? 0) }}</span>
                         <span class="text-muted-foreground">Posts</span>
                    </div>
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">0</span>
                        <span class="text-muted-foreground">Followers</span>
                    </div>
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">0</span>
                        <span class="text-muted-foreground">Following</span>
                    </div>
                </div>
            </div>

            {{-- Se NÃO pode ver --}}
            @if(!$canView)
                <div class="p-6 bg-card border border-border rounded-lg text-center shadow-sm">
                    <p class="text-muted-foreground">
                        This profile is private.
                    </p>
                    <p class="text-muted-foreground text-sm mt-1">
                        Follow the user to see their posts.
                    </p>
                </div>
            @else

            <div class="border-b border-border mb-6">
                <div class="px-4 py-3">
                    <h2 class="font-semibold text-foreground">Posts</h2>
                </div>
            </div>

            {{-- Posts Display --}}
            @if(isset($posts) && $posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm transition-all duration-200 hover:shadow-xl hover:-translate-y-1 group">
                            <a href="{{ route('posts.show', $post) }}" class="block cursor-pointer">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $post->title }}
                                    </h3>
                                    <span class="text-sm text-gray-500">{{ $post->created_at->format('M j, Y') }}</span>
                                </div>

                                <div class="flex gap-4">
                                    @if($post->img)
                                    <div class="flex-shrink-0 w-48 overflow-hidden rounded-md">
                                        <img 
                                            src="{{ $post->img }}" 
                                            alt="Post image" 
                                            class="w-full h-48 object-cover transition-all duration-200 group-hover:scale-105"
                                        >
                                    </div>
                                    @endif

                                    <div class="flex-1 min-w-0"> 
                                        <div class="max-h-48 overflow-hidden relative"> 
                                            <p class="text-gray-600 whitespace-pre-line leading-relaxed" id="text-{{ $post->id }}">{{ trim($post->description ?? '') }}</p>
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

                            <div class="flex justify-between items-center text-sm text-gray-500 mt-4 pt-4 border-t border-gray-200">
                                <div class="flex gap-4">
                                    <button class="reaction-btn flex items-center gap-1 hover:text-blue-600 transition-colors" 
                                            data-post-id="{{ $post->id }}" 
                                            data-type="like"
                                            data-likes-count="{{ $post->likes }}">
                                        <svg class="w-5 h-5 like-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span class="likes-count">{{ $post->likes }}</span>
                                    </button>

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

                                    <div class="flex items-center gap-1 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <span>0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted-foreground">
                    No posts yet.
                </p>
            @endif

            @endif
        </div>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Show fade with "Continue reading" only when text overflows
    document.querySelectorAll('[id^="text-"]').forEach(textElement => {
        const postId = textElement.id.replace('text-', '');
        const fadeElement = document.getElementById(`fade-${postId}`);
        const container = textElement.parentElement;
        
        if (fadeElement && textElement.scrollHeight > container.clientHeight) {
            fadeElement.classList.remove('hidden');
        }
    });
});
</script>
@endsection
