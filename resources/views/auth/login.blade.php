@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4">
    <div class="w-full max-w-md space-y-8">

        {{-- Logo --}}
        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-2 mb-2">
                {{-- Icone pode ser outro, substitui se quiseres --}}
                <svg class="h-10 w-10 text-primary" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M9 18V5l12-2v13"></path>
                    <circle cx="6" cy="18" r="3"></circle>
                </svg>

                <span class="text-4xl font-bold tracking-tight">mufetti.</span>
            </a>
            <p class="text-muted-foreground mt-2">Share your music, discover new sounds</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-foreground mb-6 text-center">Welcome Back</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50"
                    >
                    @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="rounded">
                    Remember me
                </label>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 rounded-md transition"
                >
                    Log In
                </button>
            </form>

            {{-- Forgot password --}}
            <div class="mt-4 text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
                    Forgot password?
                </a>
            </div>

            {{-- Success message --}}
            @if (session('status'))
                <p class="text-green-500 mt-4 text-center">{{ session('status') }}</p>
            @endif
        </div>

        {{-- Sign Up --}}
        <div class="bg-card border border-border rounded-lg p-4 text-center shadow-sm">
            <p class="text-sm text-muted-foreground">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">
                    Sign up
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
