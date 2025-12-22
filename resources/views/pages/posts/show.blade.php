@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-12">

        {{-- Back Button Inteligente --}}
        <div class="mb-6">
            @if(auth()->check() && auth()->user()->is_admin && str_contains(url()->previous(), 'admin/content'))
                {{-- Redirecionamento para o Painel de Admin se vier de lá --}}
                <a href="{{ route('admin.content.index') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:text-primary/80 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Admin Panel
                </a>
            @else
                {{-- Comportamento Normal para utilizadores --}}
                @if (auth()->id() === $post->ownerUser->id)
                <a href="{{ route('pages.profile.show', $post->ownerUser) }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to {{ $backLabel ?? (optional($post->ownerUser)->name ?? 'Profile') }}
                </a>
                @else
                <a href="{{ route('profile.showOther', $post->ownerUser) }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to {{ $backLabel ?? (optional($post->ownerUser)->name ?? 'Profile') }}
                </a>
                @endif
            @endif
        </div>

        {{-- Post Card --}}
        <div class="bg-card border border-border rounded-lg p-6 shadow-sm">

            {{-- Post Header --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-foreground mb-2">
                        {{ $post->title }}
                    </h1>

                    @if ($post->group)
                        <a href="{{ route('groups.show', $post->group->id) }}" class="text-sm px-2 py-0.5 rounded-full bg-muted text-muted-foreground hover:bg-blue-100 hover:text-blue-700 transition">
                            {{ $post->group->name }}
                        </a>
                    @endif

                    <div class="flex items-center gap-2 text-sm text-muted-foreground mt-2">
                        <a href="{{ route('pages.profile.show', $post->ownerUser) }}" class="font-medium hover:text-blue-700 transition">{{ $post->ownerUser->name }}</a>
                        <span>•</span>
                        <span>{{ $post->created_at->format('M j, Y') }}</span>
                    </div>
                </div>

                {{-- Post actions dropdown --}}
                @if(!$post->isDeleted() &&
                    (auth()->id() === $post->ownerUser->id ||
                    ($post->group && auth()->id() === $post->group->owner) ||
                    (auth()->check() && auth()->user()->is_admin)))
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="text-muted-foreground hover:text-foreground transition-colors p-1 rounded hover:bg-muted"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-card rounded-md shadow-lg border border-border z-10"
                        x-cloak
                    >
                        <div class="py-1">
                            <a href="{{ route('posts.edit', $post) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Post
                            </a>

                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="hover:bg-muted">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Delete this post?')">
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
                    <img src="{{ asset('storage/' . $post->img) }}" alt="Post image" class="w-full h-auto max-h-[400px] min-h-[200px] object-contain rounded-lg bg-white">
                </div>
            </div>
            @endif

            {{-- Post Content --}}
            <div class="prose prose-lg max-w-none mb-8">
                <p class="text-foreground whitespace-pre-line leading-relaxed text-lg">
                    {{ $post->description }}
                </p>
            </div>

            {{-- Reaction Footer --}}
            <div class="flex justify-between items-center text-sm text-muted-foreground pt-6 border-t border-border">
                <div class="flex gap-6 flex-1">
                    @if($post->isDeleted())
                        <span class="text-muted-foreground">Post Deleted</span>
                    @else
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
                        <div class="flex items-center gap-1 text-muted-foreground">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span>{{ $post->replies->count() }}</span>
                        </div>
                    @endif
                </div>

                {{-- Report Button - Positioned on the right, only show if user is not the post owner --}}
                @if(auth()->check() && auth()->id() !== $post->owner)
                <button class="flex items-center gap-1.5 text-muted-foreground hover:text-red-600 transition-colors text-xs" onclick="openReportModal('report-modal-{{ $post->id }}')" title="Report this post">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    <span class="font-medium">Report</span>
                </button>

                <!-- Report Modal (only rendered once, hidden by default) -->
                <div id="report-modal-{{ $post->id }}" class="modal-overlay hidden" onclick="if(event.target === this) closeReportModal('report-modal-{{ $post->id }}')">
                    <div class="bg-card rounded-lg shadow-lg p-8 w-full max-w-md relative animate-fade-in">
                        <button class="absolute top-2 right-2 text-muted-foreground hover:text-foreground text-2xl font-bold" onclick="closeReportModal('report-modal-{{ $post->id }}')">&times;</button>
                        <h2 class="text-xl font-semibold mb-4 text-foreground">Report Post</h2>
                        <form method="POST" action="{{ route('report.store') }}" onsubmit="event.preventDefault(); submitReportForm(this);">
                            @csrf
                            <input type="hidden" name="reportable_id" value="{{ $post->id }}">
                            <input type="hidden" name="reportable_type" value="post">
                            <label for="motive-{{ $post->id }}" class="block mb-2 font-medium">Reason</label>
                            <select name="motive" id="motive-{{ $post->id }}" class="w-full mb-4 border rounded p-2" required>
                                <option value="">Select a reason</option>
                                <option value="Spam">Spam</option>
                                <option value="Harassment">Harassment</option>
                                <option value="Inappropriate Content">Inappropriate Content</option>
                                <option value="Misinformation">Misinformation</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="description-{{ $post->id }}" class="block mb-2 font-medium">Description (optional)</label>
                            <textarea name="description" id="description-{{ $post->id }}" class="w-full border rounded p-2 mb-4" rows="2"></textarea>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 w-full">Submit Report</button>
                        </form>
                        <div class="mt-2 text-green-600 hidden text-center" id="report-success-{{ $post->id }}">Report submitted!</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="mt-8">
            <div class="bg-card border border-border rounded-lg p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">Comments ({{ $post->replies->count() }})</h3>

                @auth
                    @if(!$post->isDeleted())
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-6">
                        @csrf
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-border rounded-lg focus:ring-2 focus:ring-primary outline-none" placeholder="Write a comment..." required></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition">Post Comment</button>
                        </div>
                    </form>
                    @endif
                @endauth

                <div class="space-y-4">
                    @foreach($post->replies->sortByDesc('created_at') as $comment)
                        @if(!$comment->isDeleted())
                            @include('partials.comment-card', ['comment' => $comment])
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function openReportModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeReportModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function submitReportForm(form) {
    const modal = form.closest('.modal-overlay');
    const successMsg = modal.querySelector('[id^="report-success-"]');
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            form.style.display = 'none';
            successMsg.style.display = 'block';
            setTimeout(() => { modal.classList.add('hidden'); form.style.display = ''; successMsg.style.display = 'none'; }, 1500);
        }
    });
}
</script>

{{-- Add Alpine.js for dropdown functionality --}}
<script src="//unpkg.com/alpinejs" defer></script>
<style>[x-cloak] { display: none !important; }</style>
@endsection