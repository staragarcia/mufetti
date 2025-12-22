@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back Button --}}
        <div class="mb-8">
            @php
                $backToAdmin = auth()->check() && auth()->user()->is_admin && str_contains(url()->previous(), 'admin/content');
                $owner = $post->ownerUser;
            @endphp

            <a href="{{ $backToAdmin ? route('admin.content.index') : (auth()->id() === $owner->id ? route('pages.profile.show', $owner) : route('profile.showOther', $owner)) }}" 
               class="text-[10px] font-black text-slate-400 hover:text-[rgb(13,162,231)] flex items-center gap-2 uppercase tracking-[0.2em] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                {{ $backToAdmin ? 'Back to Admin Panel' : 'Back to ' . ($owner->name ?? 'Profile') }}
            </a>
        </div>

        {{-- Post Card --}}
        <div class="bg-white border border-slate-100 rounded-2xl p-6 sm:p-10 shadow-sm">

            {{-- Post Header --}}
            <div class="flex justify-between items-start mb-8">
                <div class="space-y-3">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight leading-tight">
                            {{ $post->title }}
                        </h1>

                        @if ($post->group)
                            <a href="{{ route('groups.show', $post->group->id) }}" class="text-[10px] font-bold px-2.5 py-1 rounded bg-slate-50 text-[rgb(13,162,231)] border border-slate-100 uppercase tracking-wider">
                                {{ $post->group->name }}
                            </a>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3 text-sm">
                        <img src="{{ $owner->avatar }}" class="w-6 h-6 rounded-full object-cover border border-slate-100">
                        <div class="text-slate-400">
                            por <a href="{{ route('profile.showOther', $owner) }}" class="font-bold text-slate-600 hover:text-[rgb(13,162,231)] transition-colors">{{ $owner->name }}</a>
                            <span class="mx-1">•</span>
                            <span>{{ $post->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Dropdown de Opções (Alpine.js) --}}
                @if(!$post->isDeleted() && (auth()->id() === $owner->id || (auth()->check() && auth()->user()->is_admin)))
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-slate-300 hover:text-slate-500 p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-xl shadow-xl z-50 py-2 overflow-hidden animate-in fade-in zoom-in duration-200">
                        <a href="{{ route('posts.edit', $post) }}" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Post
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this post?')" class="flex items-center gap-3 w-full px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete Post
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            {{-- Post Image --}}
            @if($post->img)
            <div class="mb-10 rounded-xl overflow-hidden bg-slate-50 border border-slate-100">
                <img src="{{ asset('storage/' . $post->img) }}" alt="Post image" class="w-full h-auto max-h-[500px] object-contain mx-auto">
            </div>
            @endif

            {{-- Post Content --}}
            <div class="text-slate-700 text-lg leading-relaxed mb-12 whitespace-pre-line">
                {{ $post->description }}
            </div>

            {{-- Footer Ações --}}
            <div class="flex justify-between items-center pt-8 border-t border-slate-50">
                <div class="flex gap-8 items-center">
                    @if($post->isDeleted())
                        <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Deleted Content</span>
                    @else
                        {{-- Like --}}
                        <button class="reaction-btn flex items-center gap-2 text-slate-400 hover:text-[rgb(13,162,231)] transition-all group" data-post-id="{{ $post->id }}" data-type="like">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="text-sm font-bold likes-count">{{ $post->likes }}</span>
                        </button>

                        {{-- Confetti --}}
                        <button class="reaction-btn flex items-center gap-2 text-slate-400 hover:text-amber-500 transition-all group" data-post-id="{{ $post->id }}" data-type="confetti">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4M12 22v-4M2 12h4M22 12h-4M5 5l3 3M19 5l-3 3M5 19l3-3M19 19l-3-3"/></svg>
                            <span class="text-sm font-bold confetti-count">{{ $post->comments }}</span>
                        </button>

                        {{-- Comentários Count --}}
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span class="text-sm font-bold">{{ $post->replies->count() }}</span>
                        </div>
                    @endif
                </div>

                @if(auth()->check() && auth()->id() !== $post->owner && !$post->isDeleted())
                    <button onclick="openReportModal('report-modal-{{ $post->id }}')" class="text-[10px] font-black text-slate-300 hover:text-red-400 uppercase tracking-[0.15em] transition-colors">
                        Report
                    </button>
                @endif
            </div>
        </div>

        {{-- Section: Comments --}}
        <div class="mt-12 space-y-8">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Comments ({{ $post->replies->count() }})</h3>

            @auth
                @if(!$post->isDeleted())
                <form action="{{ route('comments.store', $post) }}" method="POST" class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    @csrf
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:bg-white focus:ring-1 focus:ring-[rgb(13,162,231)] outline-none transition-all resize-none" placeholder="Share your thoughts..." required></textarea>
                    <div class="flex justify-end mt-3">
                        <button type="submit" class="bg-[rgb(13,162,231)] text-white px-8 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:brightness-105 transition-all shadow-md shadow-[rgb(13,162,231)]/10">
                            Post Comment
                        </button>
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
    </main>
</div>

{{-- Modal de Report --}}
@if(auth()->check() && auth()->id() !== $post->owner)
<div id="report-modal-{{ $post->id }}" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4" onclick="if(event.target === this) closeReportModal('report-modal-{{ $post->id }}')">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl relative animate-in zoom-in duration-200">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Report Content</h2>
        <form method="POST" action="{{ route('report.store') }}" onsubmit="event.preventDefault(); submitReportForm(this);">
            @csrf
            <input type="hidden" name="reportable_id" value="{{ $post->id }}">
            <input type="hidden" name="reportable_type" value="post">
            
            <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Reason</label>
            <select name="motive" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-3 text-sm mb-4 outline-none focus:border-red-400 transition-all mt-1" required>
                <option value="Spam">Spam</option>
                <option value="Harassment">Harassment</option>
                <option value="Inappropriate Content">Inappropriate Content</option>
                <option value="Other">Other</option>
            </select>

            <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Details (Optional)</label>
            <textarea name="description" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-3 text-sm mb-6 outline-none focus:border-red-400 transition-all mt-1 resize-none" rows="3"></textarea>
            
            <button type="submit" class="w-full bg-red-500 text-white py-4 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                Submit Report
            </button>
        </form>
        <div id="report-success-{{ $post->id }}" class="hidden text-center text-emerald-500 font-bold mt-4">Report submitted!</div>
    </div>
</div>
@endif

<script>
function openReportModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeReportModal(id) { document.getElementById(id).classList.add('hidden'); }
function submitReportForm(form) {
    const modal = form.closest('.fixed');
    const successMsg = modal.querySelector('[id^="report-success-"]');
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            reportable_id: form.reportable_id.value,
            reportable_type: form.reportable_type.value,
            motive: form.motive.value,
            description: form.description.value,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            form.style.display = 'none';
            successMsg.classList.remove('hidden');
            setTimeout(() => { closeReportModal(modal.id); }, 1500);
        }
    });
}
</script>

<script src="//unpkg.com/alpinejs" defer></script>
<style>[x-cloak] { display: none !important; }</style>
@endsection