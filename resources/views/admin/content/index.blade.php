@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Content Management</h1>
                <p class="text-muted-foreground text-sm mt-1">Review posts, comments and user reports.</p>
            </div>

            <div class="relative w-full md:w-96">
                <input 
                    type="text" 
                    id="contentSearch" 
                    placeholder="Search content..." 
                    class="w-full bg-card border border-border rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-primary/20 transition-all shadow-sm"
                >
                <div class="absolute left-3 top-3 text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="flex border-b border-border mb-8 gap-8 overflow-x-auto">
            <button onclick="switchTab('posts')" id="tab-posts-btn" class="pb-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap">
                Posts Management
            </button>
            <button onclick="switchTab('comments')" id="tab-comments-btn" class="pb-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap">
                Comments Management
            </button>
            <button onclick="switchTab('reports')" id="tab-reports-btn" class="pb-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap flex items-center gap-2">
                User Reports
                @if(isset($reports) && $reports->where('status', 'pending')->count() > 0)
                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $reports->where('status', 'pending')->count() }}</span>
                @endif
            </button>
        </div>

        {{-- TAB: POSTS --}}
        <div id="tab-posts" class="tab-content space-y-8">
            <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/30 border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider">Post Title</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider">Author</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-muted-foreground tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-sm">
                        @foreach($posts as $post)
                            <tr class="hover:bg-muted/5 transition-colors content-row">
                                <td class="px-6 py-4 font-bold text-foreground">{{ $post->title }}</td>
                                <td class="px-6 py-4 text-muted-foreground">{{ "@".$post->ownerUser->username }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('posts.show', $post->id) }}" class="p-2 hover:bg-muted rounded-lg text-muted-foreground hover:text-primary transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 hover:bg-red-50 rounded-lg text-muted-foreground hover:text-red-600 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 pagination-container">{{ $posts->links() }}</div>
        </div>

        {{-- TAB: COMMENTS --}}
        <div id="tab-comments" class="tab-content hidden space-y-8">
            <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <tbody class="divide-y divide-border text-sm">
                        @foreach($comments as $comment)
                            <tr class="hover:bg-muted/5 transition-colors content-row">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-foreground">{{ "@".$comment->ownerUser->username }}:</span>
                                    <span class="text-muted-foreground">{{ Str::limit($comment->description, 100) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        @php
                                            $current = $comment;
                                            while($current->reply_to && $current->parent) { $current = $current->parent; }
                                            $rootId = $current->reply_to ?? $current->id; 
                                        @endphp
                                        <a href="{{ route('posts.show', $rootId) }}" class="p-2 hover:bg-muted rounded-lg text-muted-foreground hover:text-primary transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="p-2 hover:bg-red-50 rounded-lg text-muted-foreground hover:text-red-600 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 pagination-container">{{ $comments->links() }}</div>
        </div>

        {{-- TAB: REPORTS --}}
        <div id="tab-reports" class="tab-content hidden space-y-8">
            <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/30 border-b border-border">
                        <tr class="text-xs font-bold uppercase text-muted-foreground tracking-wider">
                            <th class="px-6 py-4">Reporter</th>
                            <th class="px-6 py-4">Target User</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Motive</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-sm">
                        @foreach($reports as $report)
                            @php
                                $reportedUser = null;
                                if($report->reportable_type === 'post' || $report->reportable_type === 'comment') {
                                    $content = \App\Models\Content::find($report->reportable_id);
                                    $reportedUser = $content ? $content->ownerUser : null;
                                }
                            @endphp
                            <tr class="hover:bg-muted/5 transition-colors content-row">
                                <td class="px-6 py-4 text-muted-foreground">{{ $report->user->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 font-bold text-foreground">
                                    {{ $reportedUser->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="capitalize px-2 py-0.5 bg-muted rounded text-[10px] font-bold">{{ $report->reportable_type }}</span>
                                </td>
                                <td class="px-6 py-4 text-foreground truncate max-w-[150px]" title="{{ $report->description }}">
                                    {{ $report->motive }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2 py-1 rounded text-[10px] font-bold uppercase
                                        @if($report->status=='pending') bg-yellow-100 text-yellow-800
                                        @elseif($report->status=='reviewed') bg-green-100 text-green-800
                                        @elseif($report->status=='dismissed') bg-gray-200 text-gray-600
                                        @endif">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        @if($report->reportable_type === 'post')
                                            <a href="{{ route('posts.show', $report->reportable_id) }}" class="text-primary hover:underline text-xs font-bold" target="_blank">View</a>
                                        @elseif($report->reportable_type === 'comment')
                                            @php
                                                $comment = \App\Models\Content::find($report->reportable_id);
                                                $postId = $comment ? $comment->reply_to : null;
                                            @endphp
                                            <a href="{{ route('posts.show', $postId ?? $report->reportable_id) }}" class="text-primary hover:underline text-xs font-bold" target="_blank">View</a>
                                        @endif

                                        <form method="POST" action="{{ route('admin.reports.updateStatus', $report->id) }}">
                                            @csrf
                                            <select name="status" class="bg-background border border-border rounded px-2 py-1 text-xs outline-none focus:ring-2 focus:ring-primary/20" onchange="this.form.submit()">
                                                <option value="pending" @if($report->status=='pending') selected @endif>Pending</option>
                                                <option value="reviewed" @if($report->status=='reviewed') selected @endif>Reviewed</option>
                                                <option value="dismissed" @if($report->status=='dismissed') selected @endif>Dismissed</option>
                                            </select>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        switchTab(urlParams.get('tab') || 'posts');
    }

    document.getElementById('contentSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const activeTabContent = document.querySelector('.tab-content:not(.hidden)');
        const rows = activeTabContent.querySelectorAll('.content-row');

        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        
        document.querySelectorAll('[id$="-btn"]').forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-muted-foreground');
        });

        const activeBtn = document.getElementById('tab-' + tab + '-btn');
        activeBtn.classList.add('border-primary', 'text-primary');
        activeBtn.classList.remove('border-transparent', 'text-muted-foreground');

        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    }
</script>
@endsection