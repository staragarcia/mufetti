@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-4xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-foreground">Search results</h1>

        <p class="text-muted-foreground">
            Showing <strong>{{ $type }}</strong> results for:
            <span class="text-foreground">"{{ $query }}"</span>
        </p>

        <a href="{{ route('search.show') }}" class="text-primary hover:underline text-sm">
            ← Back to search
        </a>

        <div class="space-y-4 mt-6">

            @forelse ($results as $result)

                @if ($type === 'posts')
                    @include('partials.post-card', ['post' => $result])
                @endif

                @if ($type === 'comments')
                    @include('partials.comment-card', ['comment' => $result])
                @endif

                @if ($type === 'groups')
                    @include('partials.group-card', ['group' => $result])
                @endif

                @if ($type === 'users')
                    @include('partials.user-card', ['user' => $result])
                @endif

            @empty
                <p class="text-muted-foreground">No results found.</p>
            @endforelse

        </div>

    </div>
</div>
@endsection

