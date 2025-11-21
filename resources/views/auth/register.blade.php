@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8">

        {{-- Logo --}}
        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-2 mb-2">
                <svg class="h-10 w-10 text-primary" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M9 18V5l12-2v13"></path>
                    <circle cx="6" cy="18" r="3"></circle>
                </svg>
                <span class="text-4xl font-bold tracking-tight">mufetti.</span>
            </a>
            <p class="text-muted-foreground mt-2">Join the music community</p>
        </div>

        {{-- Signup Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-foreground mb-6 text-center">Create Account</h2>

            {{-- Show validation errors --}}
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Full Name --}}
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Sarah Chen">
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label for="username" class="text-sm font-medium">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="sarahmusic">
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="your@email.com">
                </div>

                {{-- Birth Date --}}
                <div class="space-y-2">
                    <label for="birth_date" class="text-sm font-medium">Birth Date</label>
                    <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none">
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label for="description" class="text-sm font-medium">Bio (optional)</label>
                    <textarea id="description" name="description"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 h-24 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Tell us about yourself">{{ old('description') }}</textarea>
                </div>

                {{-- Privacy Toggle --}}
                <div class="flex items-center gap-2">
                    <input id="is_public" type="checkbox" name="is_public" value="1"
                        {{ old('is_public') ? 'checked' : '' }}>
                    <label for="is_public" class="text-sm">Make my profile public</label>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Create a password">
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-2">
                    <label for="password-confirm" class="text-sm font-medium">Confirm Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Repeat your password">
                </div>

                <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 rounded-md transition">
                    Sign Up
                </button>
            </form>

            <p class="text-xs text-muted-foreground text-center mt-4">
                By signing up, you agree to our Terms of Service and Privacy Policy
            </p>
        </div>

        {{-- Login Link --}}
        <div class="bg-card border border-border rounded-lg p-4 text-center shadow-sm">
            <p class="text-sm text-muted-foreground">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">
                    Log in
                </a>
            </p>
        </div>

    </div>
</div>
@endsection
