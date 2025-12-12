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

