@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-3xl mx-auto space-y-8">

        {{-- Owner tools --}}
        @if(auth()->id() === $group->owner)
            <div class="flex gap-4">
                @if($group->is_public === False)
                    <a href="{{ route('groups.requests', $group->id) }}" class="text-primary underline text-sm">
                        View Join Requests
                    </a>
                @endif
                <a href="{{ route('groups.edit', $group->id) }}" class="text-primary underline text-sm">
                    Edit Group
                </a>
            </div>
        @endif

        {{-- Group Card --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">

            {{-- Group Header --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                        {{ $group->name }}
                    </h1>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="font-medium">{{ $group->ownerUser->name }}</span>
                    </div>
                </div>

                {{-- Post actions dropdown --}}
                @if(auth()->id() === $group->ownerUser->id)
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded hover:bg-gray-100"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>

                    {{-- Dropdown menu --}}
                    <div
                        x-show="open"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                        x-cloak
                    >
                        <div class="py-1">
                            {{-- Edit option --}}
                            <a
                                href="{{ route('groups.edit', $group) }}"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Group
                            </a>

                            {{-- Delete option --}}
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="hover:bg-gray-100">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:text-red-800 transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this Group?')"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Group
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        {{-- Group Header --}}
        <div class="bg-card border border-border p-6 rounded-lg shadow-sm space-y-4">

            <h1 class="text-3xl font-bold text-foreground">{{ $group->name }}</h1>
            <p class="text-muted-foreground">{{ $group->description }}</p>

            <div class="text-sm text-muted-foreground flex gap-4 items-center">

                {{-- Show members toggle --}}
                <button onclick="toggleMembers()" class="underline hover:text-foreground">
                    {{ $group->member_count }} members
                </button>

                <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
            </div>

            {{-- Lista de membros --}}
            <div id="membersList" class="hidden mt-4">
                <h2 class="font-semibold text-foreground text-lg mb-2">Members</h2>

                <div class="flex flex-wrap gap-3">
                    @foreach($members as $member)
                        <div class="bg-secondary px-3 py-1 rounded text-sm flex items-center gap-2">
                            {{ $member->username }}

                            {{-- Owner can remove members --}}
                            @if(auth()->id() === $group->owner && $member->id !== $group->owner)
                                <form action="{{ route('groups.members.remove', [$group->id, $member->id]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remove this member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 text-xs hover:text-red-700">Remove</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                function toggleMembers() {
                    const div = document.getElementById('membersList');
                    div.classList.toggle('hidden');
                }
            </script>

            {{-- Member leave group --}}
            @if($isMember && auth()->id() !== $group->owner)
                <form action="{{ route('groups.leave', $group->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Leave Group
                    </button>
                </form>
            @endif

            {{-- Member add post --}}
            @if($isMember)
                <a href="{{ route('posts.create.withGroup', $group) }}" class="mt-4">
                    @csrf
                    <button class="px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500">
                        Add Post
                    </button>
                <a>
            @endif

        </div>

        {{-- BOTÃO JOIN - mesmo se grupo for publico --}}
        @if(!$isMember)
            <div class="bg-card border border-border rounded-lg text-center p-6">

                @if($group->is_public)
                    {{-- Join instantly --}}
                    <form method="POST" action="{{ route('groups.join.public', $group->id) }}">
                        @csrf
                        <button class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">
                            Join Group
                        </button>
                    </form>

                @else
                    {{-- Private group --}}
                    @if($hasPendingRequest)
                        <p class="text-yellow-500 mt-2">Join request pending...</p>
                    @else
                        <form method="POST" action="{{ route('groups.join', $group->id) }}">
                            @csrf
                            <button class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">
                                Request to Join
                            </button>
                        </form>
                    @endif
                @endif

            </div>
        @endif


        {{-- Content --}}
        @if($canView)

            {{-- Posts Display --}}
                @include('partials.showPosts', ['posts' => $posts, 'group' => $group])
        @endif

    </div>
</div>

@endsection

