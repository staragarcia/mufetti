<a href="{{ route('profile.showOther', $user->id) }}"
   class="flex items-center gap-4 bg-card border border-border rounded-lg p-4 shadow-sm hover:bg-muted/40 transition">

    <img src="{{ $user->profile_picture ?? '/default-avatar.png' }}"
         class="w-12 h-12 rounded-full object-cover">

    <div>
        <h3 class="font-semibold text-foreground text-lg">{{ $user->username }}</h3>
        <p class="text-muted-foreground text-sm">{{ $user->name }}</p>
    </div>
</a>

