<div class="space-y-4">

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

