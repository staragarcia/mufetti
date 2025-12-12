@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4">
    <div class="w-full max-w-md space-y-8">

        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-2 mb-2">
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

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 rounded-md transition"
                >
                    Log In
                </button>
            </form>

            <!-- need to make it prettier -->
            <a href="{{ route('google-auth') }}">
                <div class="px-6 sm:px-0 max-w-sm">
                    <button type="button" class="text-white w-full  bg-[#4285F4] hover:bg-[#4285F4]/90 focus:ring-4 focus:outline-none focus:ring-[#4285F4]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center justify-between mr-2 mb-2"><svg class="mr-2 -ml-1 w-4 h-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512"><path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path></svg>Sign up with Google<div></div></button>
                </div>
            </a>

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
