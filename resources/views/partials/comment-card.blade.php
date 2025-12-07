<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm" x-data="{ editing: false, replying: false }">
    
    {{-- Comment Header with Profile Picture --}}
    <div class="flex gap-3 mb-3">
        {{-- Profile Picture --}}
        <a href="{{ route('profile.showOther', $comment->ownerUser) }}" class="flex-shrink-0">
            <img 
                src="{{ $comment->ownerUser->profile_picture ?? '/placeholder.jpg' }}" 
                alt="{{ $comment->ownerUser->username ?? 'Unknown' }}"
                class="w-8 h-8 rounded-full object-cover border-2 border-gray-300"
                onerror="this.src='/placeholder.jpg'"
            >
        </a>

        <div class="flex-1 min-w-0">
            {{-- User Info and Timestamp --}}
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('profile.showOther', $comment->ownerUser) }}" class="font-semibold text-gray-900 hover:text-blue-600 text-sm">
                        {{ $comment->ownerUser->username ?? 'Unknown' }}
                    </a>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                </div>

                {{-- Comment Actions (Edit/Delete) --}}
                @auth
                    @if(auth()->id() === $comment->owner)
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open"
                                class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded hover:bg-gray-200"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                                x-cloak
                            >
                                <div class="py-1">
                                    <button 
                                        @click="editing = true; open = false"
                                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="hover:bg-gray-100">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:text-red-800"
                                            onclick="return confirm('Are you sure you want to delete this comment?')"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- Comment Content (View Mode) --}}
            <div x-show="!editing">
                <p class="text-gray-700 text-sm whitespace-pre-line mb-2">{{ $comment->description }}</p>
                
                {{-- Reply Button --}}
                @auth
                    <button 
                        @click="replying = !replying"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        <span x-text="replying ? 'Cancel' : 'Reply'"></span>
                    </button>
                @endauth
            </div>

            {{-- Comment Edit Form --}}
            <div x-show="editing" x-cloak>
                <form action="{{ route('comments.update', $comment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <textarea 
                        name="description" 
                        rows="3" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none mb-2"
                        required
                    >{{ $comment->description }}</textarea>
                    <div class="flex gap-2 justify-end">
                        <button 
                            type="button"
                            @click="editing = false"
                            class="px-3 py-1 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>

            {{-- Reply Form --}}
            @auth
                <div x-show="replying" x-cloak class="mt-3">
                    <form action="{{ route('comments.reply', $comment) }}" method="POST" class="bg-white border border-gray-300 rounded-lg p-3">
                        @csrf
                        <div class="flex gap-2 mb-2">
                            <img 
                                src="{{ auth()->user()->profile_picture ?? '/placeholder.jpg' }}" 
                                alt="{{ auth()->user()->username }}"
                                class="w-6 h-6 rounded-full object-cover border border-gray-300 flex-shrink-0"
                                onerror="this.src='/placeholder.jpg'"
                            >
                            <textarea 
                                name="description" 
                                rows="2" 
                                class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                placeholder="Write a reply to {{ $comment->ownerUser->username }}..."
                                required
                            ></textarea>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button 
                                type="button"
                                @click="replying = false"
                                class="px-3 py-1 text-xs text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                            >
                                Reply
                            </button>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    {{-- Nested Replies --}}
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="ml-11 mt-3 space-y-3 border-l-2 border-gray-300 pl-3">
            @foreach($comment->replies->sortBy('created_at') as $reply)
                @include('partials.comment-card', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>

