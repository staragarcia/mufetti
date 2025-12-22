@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <div class="w-[80%] mx-auto">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Content Management</h1>
                <p class="text-slate-500 text-sm">Moderação de publicações, comentários e denúncias.</p>
            </div>

            <div class="relative w-full md:w-80">
                <input 
                    type="text" 
                    id="contentSearch" 
                    placeholder="Search content..." 
                    class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:border-[rgb(13,162,231)] transition-colors"
                >
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="flex gap-8 mb-6 border-b border-slate-100">
            <button onclick="switchTab('posts')" id="tab-posts-btn" class="tab-btn pb-4 text-sm font-bold transition-all border-b-2">
                Posts
            </button>
            <button onclick="switchTab('comments')" id="tab-comments-btn" class="tab-btn pb-4 text-sm font-bold transition-all border-b-2">
                Comments
            </button>
            <button onclick="switchTab('reports')" id="tab-reports-btn" class="tab-btn pb-4 text-sm font-bold transition-all border-b-2 flex items-center gap-2">
                Reports
                @if(isset($reports) && $reports->where('status', 'pending')->count() > 0)
                    <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded">
                        {{ $reports->where('status', 'pending')->count() }}
                    </span>
                @endif
            </button>
        </div>

        <div class="border border-slate-100 rounded-lg overflow-hidden">
            
            {{-- Tab: Posts --}}
            <div id="tab-posts" class="tab-content">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Post Title</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Author</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($posts as $post)
                            <tr class="hover:bg-slate-50/50 transition-colors content-row">
                                <td class="px-6 py-4 font-semibold text-slate-700 text-sm">{{ $post->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ "@".$post->ownerUser->username }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-3 text-slate-400">
                                        <a href="{{ route('posts.show', $post->id) }}" class="hover:text-[rgb(13,162,231)] transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete post?')">
                                            @csrf @method('DELETE')
                                            <button class="hover:text-red-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-50">{{ $posts->links() }}</div>
            </div>

            {{-- Tab: Comments --}}
            <div id="tab-comments" class="tab-content hidden">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-50">
                        @foreach($comments as $comment)
                            <tr class="hover:bg-slate-50/50 transition-colors content-row">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-800">{{ "@".$comment->ownerUser->username }}</div>
                                    <div class="text-sm text-slate-500 mt-1">"{{ Str::limit($comment->description, 100) }}"</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors uppercase tracking-wider">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">{{ $comments->links() }}</div>
            </div>

            {{-- Tab: Reports --}}
            <div id="tab-reports" class="tab-content hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Reporter</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Motive</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase text-right">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($reports as $report)
                            <tr class="content-row">
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $report->user->name ?? 'User' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $report->motive }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold uppercase {{ $report->status == 'pending' ? 'text-amber-500' : 'text-emerald-500' }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.reports.updateStatus', $report->id) }}">
                                        @csrf
                                        <select onchange="this.form.submit()" name="status" class="bg-slate-50 border border-slate-200 rounded-md px-2 py-1 text-xs font-bold text-slate-600 outline-none focus:border-[rgb(13,162,231)]">
                                            <option value="pending" @if($report->status=='pending') selected @endif>Pending</option>
                                            <option value="reviewed" @if($report->status=='reviewed') selected @endif>Resolved</option>
                                        </select>
                                    </form>
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
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-[rgb(13,162,231)]', 'text-[rgb(13,162,231)]');
            btn.classList.add('border-transparent', 'text-slate-400');
        });

        const activeBtn = document.getElementById('tab-' + tab + '-btn');
        activeBtn.classList.add('border-[rgb(13,162,231)]', 'text-[rgb(13,162,231)]');
        activeBtn.classList.remove('border-transparent', 'text-slate-400');

        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    }

    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        switchTab(urlParams.get('tab') || 'posts');
    }

    document.getElementById('contentSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.content-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>
@endsection