@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-3xl mx-auto space-y-8">
        
        <div class="mb-2">
            <a href="{{ route('groups.showUserGroups') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-primary transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Groups
            </a>
        </div>

        @if(!$group->is_active)
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-amber-800 font-bold uppercase text-sm tracking-wide">Group Temporarily Inactive</p>
                    <p class="text-amber-700 text-xs">Activity is paused until an administrator reactivates the group.</p>
                </div>
            </div>
        @endif

        <div class="bg-card border border-border p-6 rounded-lg shadow-sm space-y-4">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground {{ !$group->is_active ? 'text-slate-400' : '' }}">{{ $group->name }}</h1>
                    <p class="text-muted-foreground">{{ $group->description }}</p>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                        <span class="font-medium">Owner: {{ $group->ownerUser->name }}</span>
                    </div>
                </div>

                @if(auth()->id() === $group->ownerUser->id || (auth()->user() && auth()->user()->is_admin))
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10" x-cloak>
                        <div class="py-1">
                            <a href="{{ route('groups.edit', $group) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit Group
                            </a>
                            <form action="{{ route('groups.destroy', $group) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Delete this Group?')">
                                    Delete Group
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="text-sm text-muted-foreground flex gap-4 items-center">
                <a href="{{ route('groups.members', $group) }}" class="hover:underline">
                    <span class="font-semibold text-foreground">{{ $group->member_count }}</span> Members
                </a>
                <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
            </div>

            @if($group->is_active)
                <div class="flex flex-wrap gap-3 mt-4">
                    @if(auth()->id() === $group->owner && !$group->is_public)
                        <a href="{{ route('groups.requests', $group->id) }}" class="px-4 py-2 border border-primary text-primary rounded hover:bg-primary/5 text-sm font-medium transition">
                            View Join Requests
                        </a>
                    @endif

                    @if($isMember)
                        @if(auth()->id() !== $group->owner)
                            @if(!auth()->user()->is_blocked)
                                <form action="{{ route('groups.leave', $group->id) }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium transition">Leave Group</button>
                                </form>
                            @else
                                <span class="bg-red-100 text-red-600 font-semibold py-2 px-4 rounded-md cursor-not-allowed text-sm" title="Account Restricted">
                                    Leave Group (Blocked)
                                </span>
                            @endif
                        @endif
                        
                        @if(!auth()->user()->is_blocked)
                            <a href="{{ route('posts.create.withGroup', $group) }}" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90 text-sm font-medium transition">
                                Add Post
                            </a>
                        @else
                            <span class="bg-red-300 text-primary-foreground font-semibold py-2 px-4 rounded-md cursor-not-allowed text-sm" title="Account Restricted">
                                Add Post (Blocked)
                            </span>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        @if($group->is_active)
            @if(!$isMember)
                <div class="bg-card border border-border rounded-lg text-center p-6">
                    @if(auth()->user()->is_blocked)
                        <span class="bg-red-300 text-primary-foreground font-semibold py-2 px-6 rounded-md cursor-not-allowed inline-block" title="Account Restricted">
                            Join Group (Blocked)
                        </span>
                    @else
                        @if($group->is_public)
                            <form method="POST" action="{{ route('groups.join.public', $group->id) }}">
                                @csrf
                                <button class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90 transition">Join Group</button>
                            </form>
                        @else
                            @if($hasPendingRequest)
                                <p class="text-yellow-600 font-medium">Join request pending approval...</p>
                            @else
                                <form method="POST" action="{{ route('groups.join', $group->id) }}">
                                    @csrf
                                    <button class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90 transition">Request to Join</button>
                                </form>
                            @endif
                        @endif
                    @endif
                </div>
            @endif

            @if($canView)
                @include('partials.showPosts', ['posts' => $posts, 'group' => $group])
            @endif
        @else
            <div class="py-16 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                <h3 class="text-lg font-semibold text-gray-600">Locked Content</h3>
                <p class="text-gray-400">This group's activity is paused.</p>
            </div>
        @endif
    </div>
</div>
@endsection