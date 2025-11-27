@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10">
    <div class="bg-card border border-border rounded-lg p-6 shadow-sm">
        <h1 class="text-xl font-semibold mb-4">Create User</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-sm text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Name</label>
                    <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-md border border-border px-3 py-2" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Username</label>
                    <input name="username" value="{{ old('username') }}" class="mt-1 w-full rounded-md border border-border px-3 py-2" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border border-border px-3 py-2" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Password (optional)</label>
                    <input name="password" type="password" class="mt-1 w-full rounded-md border border-border px-3 py-2" />
                    <p class="text-xs text-muted-foreground mt-1">If left blank, password will be "password". Change it after creation.</p>
                </div>

                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }} class="rounded">
                        <span class="text-sm">Public</span>
                    </label>

                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }} class="rounded">
                        <span class="text-sm">Admin</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Profile picture URL</label>
                    <input name="profile_picture" value="{{ old('profile_picture') }}" class="mt-1 w-full rounded-md border border-border px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Birth date</label>
                    <input name="birth_date" type="date" value="{{ old('birth_date', \Carbon\Carbon::now()->subYears(20)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-border px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-muted-foreground">Bio</label>
                    <textarea name="description" rows="4" class="mt-1 w-full rounded-md border border-border px-3 py-2">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-primary text-white rounded-md">Create user</button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-muted text-muted-foreground rounded-md">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection