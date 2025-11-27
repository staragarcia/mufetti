@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">

        <div class="relative h-48 sm:h-64 rounded-xl overflow-hidden mb-4"
            style="background-color: hsl(199,89%,48%);">
            <div class="absolute inset-0 bg-linear-to-t from-black/25 to-transparent"></div>
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
                            @if(auth()->check() && auth()->id() === $user->id)
                                <a href="{{ route('pages.profile.edit') }}"
                                class="px-4 py-2 rounded-md border border-border text-sm font-medium hover:bg-muted transition">
                                    Edit Profile
                                </a>
                            @endif
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
                    <h2 class="font-semibold text-foreground">Posts</h2>
                </div>
            </div>

            <div class="border-b border-border mb-6">


        @if($posts->isEmpty())
            <p class="text-muted-foreground">
                No posts yet.
            </p>
        @else
            <div class="space-y-6">
                @foreach($posts as $post)
                    <a href="{{ route('posts.show', $post->id) }}"
                    class="block bg-card border border-border rounded-lg p-4 hover:bg-muted transition">

                        <h3 class="text-lg font-semibold text-foreground">{{ $post->title }}</h3>

                        @if($post->img)
                            <img src="{{ $post->img }}" 
                                class="w-full h-48 object-cover rounded-md mt-3" />
                        @endif

                        <p class="text-muted-foreground mt-2">
                            {{ Str::limit($post->description, 150) }}
                        </p>

                        <p class="text-xs text-muted-foreground mt-2">
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif

        </div>
    </main>
</div>
@endsection
