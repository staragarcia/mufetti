@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">

        <div class="relative h-48 sm:h-64 rounded-xl overflow-hidden mb-4 bg-muted">
            @if($user->profile_picture)
                <img src="{{ $user->profile_picture }}" class="w-full h-full object-cover" />
            @else
                <div class="w-full h-full bg-muted"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent"></div>
        </div>

        <div class="relative px-4 sm:px-6">

            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 -mt-16 sm:-mt-20 mb-6">

                <img
                    src="{{ $user->profile_picture ?? '/placeholder.jpg' }}"
                    alt="Avatar"
                    class="h-32 w-32 rounded-full border-4 border-background object-cover bg-muted"
                />

                <div class="flex-1 min-w-0">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-foreground">
                                {{ $user->name }}
                            </h1>
                            <p class="text-muted-foreground">{{ "@" . $user->username }}</p>
                        </div>

                        <div class="flex gap-2">
                            <a href="/profile/edit"
                               class="px-4 py-2 rounded-md border border-border text-sm hover:bg-muted">
                                Edit Profile
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-6">

                <p class="text-foreground leading-relaxed">
                    {{ $user->description ?? 'No bio yet.' }}
                </p>

                <div class="flex flex-wrap gap-4 text-sm text-muted-foreground">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>

                        <span>Joined {{ $user->created_at?->format('F Y') ?? '2024' }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        @if($user->is_public)
                            <span class="text-green-600 font-semibold">Public Profile</span>
                        @else
                            <span class="text-red-600 font-semibold">Private Profile</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-6 text-sm">
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">0</span>
                        <span class="text-muted-foreground">Posts</span>
                    </div>
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">0</span>
                        <span class="text-muted-foreground">Followers</span>
                    </div>
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">0</span>
                        <span class="text-muted-foreground">Following</span>
                    </div>
                </div>
            </div>

            <div class="border-b border-border mb-6">
                <div class="px-4 py-3">
                    <h2 class="font-semibold text-foreground">posts</h2>
                </div>
            </div>

            <p class="text-muted-foreground">
                No posts yet.
            </p>

        </div>
    </main>
</div>
@endsection
