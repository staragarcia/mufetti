@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-12">
    <div class="max-w-2xl mx-auto space-y-8">

        {{-- Back to Admin --}}
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Admin Panel
            </a>
        </div>

        <div class="text-center">
            <h2 class="text-3xl font-bold text-foreground mb-2">Create New User</h2>
            <p class="text-muted-foreground">Add a new user to the system</p>
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
                        id="create-preview-image"
                        src="/images/avatar.jpg" 
                        class="h-32 w-32 rounded-full object-cover border-2 border-border shadow-sm"
                        alt="Profile preview"
                    />
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Profile Picture Upload --}}
                <div class="space-y-2">
                    <label for="create-profile-picture" class="cursor-pointer">
                        <div class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-border rounded-md transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Upload Profile Picture</span>
                        </div>
                    </label>
                    <input 
                        id="create-profile-picture"
                        type="file" 
                        name="profile_picture" 
                        accept="image/*"
                        class="hidden"
                        onchange="loadCreatePreview(event)"
                    >
                    <p class="text-xs text-muted-foreground text-center">JPG, PNG or GIF (max 2MB)</p>
                </div>

                <script>
                    function loadCreatePreview(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('create-preview-image').src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                </script>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Birth Date</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', \Carbon\Carbon::now()->subYears(20)->format('Y-m-d')) }}"
                        class="w-full rounded-md border border-border bg-background px-3 py-2">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Bio</label>
                    <textarea name="description" class="w-full rounded-md border border-border bg-background px-3 py-2 h-24">{{ old('description') }}</textarea>
                </div>

                {{-- Admin-specific fields --}}
                <div class="border-t border-border pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-foreground mb-3">Admin Settings</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_public" value="1" id="is_public"
                                {{ old('is_public', true) ? 'checked' : '' }}
                                class="rounded border-border">
                            <label for="is_public" class="text-sm">Public Profile</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_admin" value="1" id="is_admin"
                                {{ old('is_admin') ? 'checked' : '' }}
                                class="rounded border-border">
                            <label for="is_admin" class="text-sm font-medium text-blue-600">Admin Privileges</label>
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="border-t border-border pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-foreground mb-3">Set Password</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Password (optional)</label>
                        <input type="password" name="password" class="w-full rounded-md border border-border bg-background px-3 py-2">
                        <p class="text-xs text-muted-foreground">If left blank, default password will be "password". User should change it after first login.</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-primary-foreground py-2 rounded-md hover:bg-primary/90 font-semibold">
                    Create User
                </button>
            </form>

        </div>
    </div>
</div>
@endsection