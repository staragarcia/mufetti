@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-12">
    <div class="max-w-2xl mx-auto space-y-8">

        {{-- Back to Admin --}}
        <div class="mb-6">
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Profile View
            </a>
        </div>

        <div class="text-center">
            <h2 class="text-3xl font-bold text-foreground mb-2">Edit User</h2>
            <p class="text-muted-foreground">Update {{ $user->name }}'s account details</p>
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

            {{-- Profile Picture Preview --}}
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="relative group">
                    <img 
                        src="{{ $user->avatar }}" 
                        class="h-32 w-32 rounded-full object-cover border-2 border-border shadow-sm"
                        alt="Profile preview"
                    />
                </div>
                
                @if($user->profile_picture)
                <form action="{{ route('admin.users.removePicture', $user) }}" method="POST" class="w-full" onsubmit="return confirm('Remove this user\'s profile picture?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 rounded-md transition text-sm font-medium text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Remove Profile Picture</span>
                    </button>
                </form>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf 
                @method('PUT')

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
                    <label class="text-sm font-medium">Bio</label>
                    <textarea name="description" class="w-full rounded-md border border-border bg-background px-3 py-2 h-24">{{ old('description', $user->description) }}</textarea>
                </div>

                {{-- Admin-specific fields --}}
                <div class="border-t border-border pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-foreground mb-3">Admin Settings</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_public" value="1" id="is_public"
                                {{ $user->is_public ? 'checked' : '' }}
                                class="rounded border-border">
                            <label for="is_public" class="text-sm">Public Profile</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_admin" value="1" id="is_admin"
                                {{ $user->is_admin ? 'checked' : '' }}
                                class="rounded border-border">
                            <label for="is_admin" class="text-sm font-medium text-blue-600">Admin Privileges</label>
                        </div>
                    </div>
                </div>

                {{-- Optional Password Change --}}
                <div class="border-t border-border pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-foreground mb-3">Change Password</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <p class="text-xs text-muted-foreground">Minimum 6 characters</p>
                    </div>
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