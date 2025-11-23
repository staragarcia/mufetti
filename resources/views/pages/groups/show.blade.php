@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-3xl mx-auto space-y-8">

        {{-- Group Header --}}
        <div class="bg-card border border-border p-6 rounded-lg shadow-sm space-y-3">
            <h1 class="text-3xl font-bold text-foreground">{{ $group->name }}</h1>
            <p class="text-muted-foreground">{{ $group->description }}</p>

            <div class="text-sm text-muted-foreground flex gap-4">
                <span>{{ $group->member_count }} members</span>
                <span>{{ $group->is_public ? 'Public Group' : 'Private Group' }}</span>
            </div>
        </div>

        {{-- Se NÃO pode ver --}}
        @if(!$canView)
            <div class="p-6 bg-card border border-border rounded-lg text-center shadow-sm">
                <p class="text-muted-foreground">
                    This group is private.
                </p>
                <p class="text-muted-foreground text-sm mt-1">
                    Join the group to see their posts.
                </p>
            </div>
        @else

        {{-- Posts --}}
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-foreground">Posts</h2>

            @forelse($posts as $post)
                <div class="bg-card border border-border p-5 rounded-lg shadow-sm space-y-2">
                    <h3 class="text-lg font-semibold text-foreground">{{ $post->title }}</h3>
                    <p class="text-muted-foreground">{{ $post->description }}</p>

                    @if($post->img)
                        <img src="{{ $post->img }}" class="rounded-md mt-3">
                    @endif
                </div>
            @empty
                <p class="text-muted-foreground">No posts in this group yet.</p>
            @endforelse
        </div>
        @endif
    </div>
</div>
@endsection

