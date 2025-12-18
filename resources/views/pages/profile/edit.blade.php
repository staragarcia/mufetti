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

            {{-- Profile Picture Preview & Remove Button (Outside Main Form) --}}
            <div class="flex flex-col items-center gap-4 mb-6">
                <label for="edit-profile-picture" class="cursor-pointer">
                    <div class="relative group">
                        <img 
                            id="edit-preview-image"
                            src="{{ $user->avatar }}" 
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
                
                @if($user->profile_picture)
                <form action="{{ route('profile.removePicture') }}" method="POST" class="w-full" onsubmit="return confirm('Remove your profile picture?');">
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

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Profile Picture Upload --}}
                <div class="space-y-2">
                    <label for="edit-profile-picture" class="cursor-pointer">
                        <div class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-border rounded-md transition text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Change Profile Picture</span>
                        </div>
                    </label>
                    <input 
                        id="edit-profile-picture"
                        type="file" 
                        name="profile_picture" 
                        accept="image/*"
                        class="hidden"
                        onchange="loadEditPreview(event)"
                    >
                    <p class="text-xs text-muted-foreground text-center">JPG, PNG or GIF (max 2MB)</p>
                </div>

                <script>
                    function loadEditPreview(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('edit-preview-image').src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                </script>

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
