<div class="bg-card border border-border rounded-lg p-5 shadow-sm space-y-2">
    <h3 class="text-lg font-semibold text-foreground">{{ $post->title }}</h3>

    <p class="text-muted-foreground text-sm">
        {{ Str::limit($post->description, 140) }}
    </p>

    <div class="text-xs text-muted-foreground flex justify-between pt-2">
        <span>By {{ $post->ownerUser->username ?? 'Unknown' }}</span>
        <span>{{ $post->created_at->diffForHumans() }}</span>
    </div>
</div>

