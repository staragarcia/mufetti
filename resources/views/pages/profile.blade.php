@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-24 p-6">

    {{-- PROFILE HEADER --}}
    <div class="bg-card border border-border rounded-lg p-6 shadow-sm">
        <h1 class="text-3xl font-bold text-primary mb-4">Your Profile</h1>

        {{-- Avatar + Name --}}
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 bg-muted rounded-full"></div>

            <div>
                <p class="text-xl font-semibold">{{ $user->name }}</p>
                <p class="text-muted-foreground">{{ '@' . $user->username }}</p>

            </div>
        </div>

        {{-- INFO BLOCK --}}
        <div class="mt-6 space-y-3">
            <p><span class="font-medium">Email:</span> {{ $user->email }}</p>

            <p>
                <span class="font-medium">Birth Date:</span>
                {{ $user->birth_date->format('Y-m-d') }}
            </p>

            <p>
                <span class="font-medium">Bio:</span>
                {{ $user->description ?? 'No bio yet.' }}
            </p>

            <p>
                <span class="font-medium">Privacy:</span>
                @if($user->is_public)
                    <span class="text-green-600 font-semibold">Public</span>
                @else
                    <span class="text-red-600 font-semibold">Private</span>
                @endif
            </p>
        </div>

        {{-- BUTTONS --}}
        <div class="mt-8 flex gap-4">

            {{-- Toggle Privacy --}}
            <form action="/profile/privacy" method="POST">
                @csrf
                <button class="px-4 py-2 rounded-md border border-border bg-primary text-primary-foreground hover:bg-primary/90">
                    Toggle Privacy
                </button>
            </form>

            {{-- Delete Account --}}
            <form action="/users/{{ $user->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 rounded-md border border-red-300 text-red-600 hover:bg-red-600 hover:text-white">
                    Delete Account
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
