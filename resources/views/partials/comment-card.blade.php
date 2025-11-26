<div class="bg-card border border-border rounded-lg p-4 shadow-sm space-y-2">
    <p class="text-foreground text-sm">
        {{ $comment->description }}
    </p>

    <div class="text-xs text-muted-foreground flex justify-between">
        <span>By {{ $comment->ownerUser->username ?? 'Unknown' }}</span>
        <span>{{ $comment->created_at->diffForHumans() }}</span>
    </div>
</div>

