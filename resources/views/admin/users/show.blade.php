@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">User #{{ $user->id }} — {{ $user->name }}</h1>

    <div class="bg-card border border-border rounded-lg p-6">
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Public:</strong> {{ $user->is_public ? 'Yes' : 'No' }}</p>
        <p><strong>Admin:</strong> {{ $user->is_admin ? 'Yes' : 'No' }}</p>
        <p class="mt-4"><strong>Bio:</strong><br>{{ $user->description ?? '—' }}</p>

        <div class="flex gap-2 mt-6">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-2 bg-primary text-white rounded">Edit</a>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-muted text-muted-foreground rounded">Back</a>
        </div>
    </div>
</div>
@endsection