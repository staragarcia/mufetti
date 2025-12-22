@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white pt-20 pb-12">
    <div class="w-[80%] mx-auto">

        <div class="mb-8 border-b border-slate-100 pb-6">
            <a href="{{ route('admin.groups.index') }}" class="text-xs font-bold text-slate-400 hover:text-[rgb(13,162,231)] flex items-center gap-2 uppercase tracking-widest transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Groups
            </a>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-slate-800">Manage Members</h1>
                <p class="text-slate-500 text-sm">Group: <span class="text-[rgb(13,162,231)] font-semibold">{{ $group->name }}</span></p>
            </div>
        </div>

        <div class="flex gap-8 mb-8 border-b border-slate-100">
            <button onclick="switchTab('members')" id="tab-members-btn" class="tab-btn pb-4 text-sm font-bold transition-all border-b-2">
                Members ({{ $members->total() }})
            </button>
            @if(!$group->is_public)
                <button onclick="switchTab('requests')" id="tab-requests-btn" class="tab-btn pb-4 text-sm font-bold transition-all border-b-2 flex items-center gap-2">
                    Join Requests
                    @if($group->join_requests_count > 0)
                        <span class="bg-orange-500 text-white text-[10px] px-1.5 py-0.5 rounded">
                            {{ $group->join_requests_count }}
                        </span>
                    @endif
                </button>
            @endif
        </div>

        <div class="mt-6">
            
            {{-- Tab: Members --}}
            <div id="tab-members" class="tab-content">
                @if($members->isEmpty())
                    <p class="text-slate-400 italic">No members found.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($members as $member)
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-lg hover:border-[rgb(13,162,231)] transition-colors">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $member->avatar }}" class="h-10 w-10 rounded-full object-cover bg-slate-100" />
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-slate-800 truncate">{{ $member->name }}</div>
                                        <div class="text-xs text-slate-400">{{ '@' . $member->username }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($member->id !== $group->owner)
                                        <form action="{{ route('groups.transferOwner', [$group->id, $member->id]) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button title="Make Owner" class="p-1.5 text-slate-400 hover:text-amber-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('groups.members.remove', [$group->id, $member->id]) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button title="Remove" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-bold text-[rgb(13,162,231)] uppercase tracking-wider">Owner</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $members->links() }}</div>
                @endif
            </div>

            {{-- Tab: Requests --}}
            <div id="tab-requests" class="tab-content hidden">
                @php $requests = $group->joinRequests; @endphp
                @if(!$requests || $requests->isEmpty())
                    <p class="text-slate-400 italic">No pending requests.</p>
                @else
                    <div class="max-w-2xl space-y-3">
                        @foreach($requests as $request)
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $request->user->avatar }}" class="h-10 w-10 rounded-full object-cover">
                                    <div class="text-sm">
                                        <div class="font-bold text-slate-800">{{ $request->user->name }}</div>
                                        <div class="text-slate-400">{{ '@' . $request->user->username }}</div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.groups.requests.accept', [$group, $request]) }}" method="POST">
                                        @csrf
                                        <button class="px-4 py-1.5 bg-[rgb(13,162,231)] text-white text-xs font-bold rounded hover:brightness-110 transition-all">Accept</button>
                                    </form>
                                    <form action="{{ route('admin.groups.requests.reject', [$group, $request]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="px-4 py-1.5 text-slate-400 hover:text-red-500 text-xs font-bold transition-all">Decline</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
        if(activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-400');
            activeBtn.classList.add('border-[rgb(13,162,231)]', 'text-[rgb(13,162,231)]');
        }
    }

    window.onload = () => switchTab('members');
</script>
@endsection