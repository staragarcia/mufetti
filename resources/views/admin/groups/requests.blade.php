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
                <h1 class="text-2xl font-bold text-slate-800">Join Requests</h1>
                <p class="text-slate-500 text-sm">Pending join requests for <span class="text-[rgb(13,162,231)] font-semibold">{{ $group->name }}</span></p>
            </div>
        </div>

        <div class="max-w-4xl">
            @forelse($requests as $req)
                <div class="flex items-center justify-between p-5 border border-slate-100 rounded-lg mb-4 hover:border-slate-200 transition-colors bg-white">
                    <div class="flex items-center gap-4">
                        @if(isset($req->user->avatar) && $req->user->avatar)
                            <img src="{{ $req->user->avatar }}" class="h-12 w-12 rounded-full object-cover bg-slate-100 shadow-sm border border-slate-50">
                        @else
                            <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center font-bold text-[rgb(13,162,231)]">
                                {{ strtoupper(substr($req->user->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div>
                            <div class="font-bold text-slate-800">{{ $req->user->name }}</div>
                            <div class="text-xs text-slate-400 font-medium">{{ '@'.$req->user->username }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <form action="{{ route('joinRequests.accept', $req->id) }}" method="POST">
                            @csrf
                            <button class="bg-[rgb(13,162,231)] text-white px-5 py-2 rounded-md text-xs font-bold hover:brightness-110 transition-all">
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('joinRequests.decline', $req->id) }}" method="POST">
                            @csrf
                            <button class="text-slate-400 hover:text-red-500 px-4 py-2 text-xs font-bold transition-colors">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 border border-dashed border-slate-200 rounded-xl">
                    <p class="text-slate-400 font-medium">No pending requests at the moment.</p>
                </div>
            @endforelse

            @if(method_exists($requests, 'links'))
                <div class="mt-8">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection