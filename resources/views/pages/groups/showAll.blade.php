@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-5xl mx-auto space-y-10">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">My Groups</h1>
                <p class="text-muted-foreground mt-1">Groups you belong to.</p>
            </div>

            <a href="{{ route('groups.create') }}"
               class="bg-primary text-primary-foreground font-semibold py-2 px-4 rounded-md hover:bg-primary/90 transition">
                Create Group
            </a>
        </div>

        {{-- Groups List Owned --}}
        <div class="border-b border-border mb-6">
            <div class="px-4 py-3">
                <h2 class="font-semibold text-foreground">Groups I own</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($groupsOwned as $group)
                <a href="{{ route('groups.show', $group->id) }}"
                   class="bg-card border border-border rounded-lg p-6 shadow-sm space-y-3 block hover:bg-muted/40 transition">

                    <h2 class="text-xl font-semibold text-foreground">
                        {{ $group->name }}
                    </h2>

                    <p class="text-muted-foreground text-sm">
                        {{ $group->description ?? 'No description available.' }}
                    </p>

                    <div class="flex justify-between items-center text-sm text-muted-foreground pt-2">
                        <span>{{ $group->member_count }} members</span>
                        <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
                    </div>
                </a>
            @empty
                <p class="text-muted-foreground">You are not a owner of any group yet. Create one!</p>
            @endforelse
        </div>

        {{-- Groups List Member --}}
        <div class="border-b border-border mb-6">
            <div class="px-4 py-3">
                <h2 class="font-semibold text-foreground">Groups I am member</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($groupsNotOwned as $group)
                <a href="{{ route('groups.show', $group->id) }}"
                   class="bg-card border border-border rounded-lg p-6 shadow-sm space-y-3 block hover:bg-muted/40 transition">

                    <h2 class="text-xl font-semibold text-foreground">
                        {{ $group->name }}
                    </h2>

                    <p class="text-muted-foreground text-sm">
                        {{ $group->description ?? 'No description available.' }}
                    </p>

                    <div class="flex justify-between items-center text-sm text-muted-foreground pt-2">
                        <span>{{ $group->member_count }} members</span>
                        <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
                    </div>
                </a>
            @empty
                <p class="text-muted-foreground">You are not a member of any group yet. Join one!</p>
            @endforelse
        </div>

    </div>
</div>
@endsection

