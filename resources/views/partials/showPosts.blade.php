@if(isset($posts) && $posts->count() > 0)
    <div class="space-y-6">
        @foreach($posts as $post)
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm transition-all duration-200 hover:shadow-xl hover:-translate-y-1 group">

                <a href="{{ route('posts.show', $post) }}" class="block cursor-pointer">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h3 class="text-xl font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                {{ $post->title }}
                            </h3>

                            @if ($post->group)
                                <span class="text-sm px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                    {{ $post->group->name }}
                                </span>
                            @endif
                        </div>

                        <span class="text-sm text-gray-500 whitespace-nowrap">
                            {{ $post->created_at->format('M j, Y') }}
                        </span>
                    </div>

                    <div class="flex gap-4">
                        @if($post->img)
                        <div class="flex-shrink-0 w-48 overflow-hidden rounded-md">
                            <img src="{{ asset('storage/' . $post->img) }}" alt="Post image" class="w-full h-48 object-cover transition-all duration-200 group-hover:scale-105">
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="max-h-48 overflow-hidden relative">
                                <p class="text-gray-600 whitespace-pre-line leading-relaxed" id="text-{{ $post->id }}">{{ trim($post->description ?? '') }}</p>
                                <div class="fade-overlay absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none hidden" id="fade-{{ $post->id }}">
                                    <div class="h-full flex items-end justify-center pb-2">
                                        <div class="flex items-center gap-1 text-primary font-semibold text-sm">
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
                        {{-- Logica de Reações com Bloqueio --}}
                        @if(!auth()->user()?->is_blocked)
                            {{-- Like Button --}}
                            <button class="reaction-btn flex items-center gap-1 hover:text-primary transition-colors"
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
                        @else
                            {{-- Estilo Bloqueado (Sem a classe reaction-btn) --}}
                            <div class="flex items-center gap-1 text-red-300 cursor-not-allowed transition-opacity opacity-70" title="Account Restricted">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ $post->likes }}</span>
                            </div>

                            <div class="flex items-center gap-1 text-red-300 cursor-not-allowed transition-opacity opacity-70" title="Account Restricted">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2v4M12 22v-4M2 12h4M22 12h-4"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5l3 3M19 5l-3 3M5 19l3-3M19 19l-3-3"/>
                                </svg>
                                <span>{{ $post->comments }}</span>
                            </div>
                        @endif

                        {{-- Comment Count (Static) --}}
                        <div class="flex items-center gap-1 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span>{{ $post->replies_count ?? $post->replies->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8">
        @if (isset($group))
            <p class="text-gray-500 mb-4">This group doesn't have any posts yet.</p>
            @if(!auth()->user()?->is_blocked)
                <a href="{{ route('posts.create.withGroup', $group) }}" class="text-primary hover:underline font-semibold">
                    Create the first post!
                </a>
            @else
                <span class="bg-red-300 text-primary-foreground font-semibold py-2 px-4 rounded-md cursor-not-allowed text-sm">
                    Post (Blocked)
                </span>
            @endif
        @else
            <p class="text-gray-500 mb-4">You haven't created any posts yet.</p>
            <a href="{{ route('posts.create') }}" class="text-primary hover:underline font-semibold">
                Create your first post!
            </a>
        @endif
    </div>
@endif