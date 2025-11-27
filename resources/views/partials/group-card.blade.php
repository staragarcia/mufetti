<a href="{{ route('groups.show', $group->id) }}"
   class="block bg-card border border-border rounded-lg p-5 shadow-sm space-y-2 hover:bg-muted/40 transition">

    <h3 class="text-lg font-semibold text-foreground">{{ $group->name }}</h3>

    <p class="text-muted-foreground text-sm">
        {{ Str::limit($group->description, 120) ?? 'No description.' }}
    </p>

    <div class="flex justify-between text-xs text-muted-foreground pt-2">
        <span>{{ $group->member_count }} members</span>
        <span>{{ $group->is_public ? 'Public' : 'Private' }}</span>
    </div>
</a>

