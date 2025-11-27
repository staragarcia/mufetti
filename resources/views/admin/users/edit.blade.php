@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Edit User — {{ $user->name }}</h1>

    <div class="bg-card border border-border rounded-lg p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="block text-sm">Name</label>
                <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border px-3 py-2" required />
            </div>

            <div class="mb-3">
                <label class="block text-sm">Username</label>
                <input name="username" value="{{ old('username', $user->username) }}" class="w-full rounded border px-3 py-2" required />
            </div>

            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded border px-3 py-2" required />
            </div>

            <div class="mb-3">
                <label class="block text-sm">Password (leave blank to keep)</label>
                <input name="password" type="password" class="w-full rounded border px-3 py-2" />
            </div>

            <div class="mb-3 flex items-center gap-4">
                <label><input type="checkbox" name="is_public" value="1" {{ $user->is_public ? 'checked' : '' }}> Public</label>
                <label><input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}> Admin</label>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Profile picture URL</label>
                <input name="profile_picture" value="{{ old('profile_picture', $user->profile_picture) }}" class="w-full rounded border px-3 py-2" />
            </div>

            <div class="mb-3">
                <label class="block text-sm">Bio</label>
                <textarea name="description" class="w-full rounded border px-3 py-2">{{ old('description', $user->description) }}</textarea>
            </div>

            <div class="flex gap-2">
                <button class="px-3 py-2 bg-primary text-white rounded">Save</button>
                <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-2 bg-muted rounded">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection