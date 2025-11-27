@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8">

        <div class="text-center">
            <h2 class="text-3xl font-bold text-foreground mb-2">Edit Profile</h2>
            <p class="text-muted-foreground">Update your account details</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif



            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Profile Picture Preview --}}
                <div class="flex flex-col items-center gap-3">
                    <img 
                        src="{{ $user->profile_picture ?? '/placeholder.jpg' }}" 
                        class="h-24 w-24 rounded-full object-cover border border-border"
                    />

                    <input type="file" name="profile_picture" class="text-sm">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Birth Date</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Bio</label>
                    <textarea name="description" class="w-full rounded-md border border-border bg-background px-3 py-2 h-24">{{ old('description', $user->description) }}</textarea>
                </div>

                {{-- Private Toggle --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_private" value="1"
                        {{ !$user->is_public ? 'checked' : '' }}>
                    <label class="text-sm">Make my profile private</label>
                </div>

                {{-- Optional Password Change --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium">New Password (optional)</label>
                    <input type="password" name="password" class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <button type="submit"
                    class="w-full bg-primary text-primary-foreground py-2 rounded-md hover:bg-primary/90 font-semibold">
                    Save Changes
                </button>
            </form>


        </div>
    </div>
</div>
@endsection
