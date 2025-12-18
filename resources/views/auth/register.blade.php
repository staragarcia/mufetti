@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-12">
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
            <p class="text-muted-foreground mt-2">Join the music community</p>
        </div>

        {{-- Signup Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-foreground mb-6 text-center">Create Account</h2>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Profile Picture Upload --}}
                <div class="flex flex-col items-center gap-4">
                    <label for="register-profile-picture" class="cursor-pointer">
                        <div class="relative group">
                            <img 
                                id="register-preview-image"
                                src="/images/avatar.jpg"
                                class="h-32 w-32 rounded-full object-cover border-2 border-border shadow-sm transition-all group-hover:border-primary"
                                alt="Profile preview"
                            />
                            <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </label>
                    
                    <div class="w-full">
                        <label for="register-profile-picture" class="cursor-pointer">
                            <div class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-border rounded-md transition text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Choose Profile Picture</span>
                            </div>
                        </label>
                        <input 
                            id="register-profile-picture"
                            type="file" 
                            name="profile_picture" 
                            accept="image/*"
                            class="hidden"
                            onchange="loadRegisterPreview(event)"
                        >
                        <p class="text-xs text-muted-foreground text-center mt-2">JPG, PNG or GIF (max 2MB)</p>
                    </div>
                </div>

                <script>
                    function loadRegisterPreview(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('register-preview-image').src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                </script>

                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Sarah Chen">
                </div>

                <div class="space-y-2">
                    <label for="username" class="text-sm font-medium">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="sarahmusic">
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="your@email.com">
                </div>

                <div class="space-y-2">
                    <label for="birth_date" class="text-sm font-medium">Birth Date</label>
                    <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none">
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-sm font-medium">Bio (optional)</label>
                    <textarea id="description" name="description"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 h-24 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Tell us about yourself">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input id="is_private" type="checkbox" name="is_private" value="1"
                        {{ old('is_private') ? 'checked' : '' }}>
                    <label for="is_private" class="text-sm">Make my profile private</label>
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:ring focus:ring-primary/50 focus:outline-none"
                        placeholder="Create a password">
                </div>

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
