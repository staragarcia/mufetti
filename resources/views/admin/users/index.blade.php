@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Admin — Users</h1>
        <a href="{{ route('admin.users.create') }}" class="px-3 py-2 bg-primary text-white rounded">Create User</a>
    </div>

    <form method="GET" class="mb-4">
        <input name="q" value="{{ $q ?? '' }}" placeholder="Search name, username, email" class="w-full rounded border px-3 py-2" />
    </form>

    <div class="bg-card border border-border rounded-lg p-4">
        <table class="w-full text-left">
            <thead>
                <tr class="text-sm text-muted-foreground">
                    <th class="py-2">#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Public</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-t">
                    <td class="py-2">{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->is_admin ? 'Yes' : 'No' }}</td>
                    <td>{{ $u->is_public ? 'Yes' : 'No' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.users.show', $u) }}" class="px-2">View</a>
                        <a href="{{ route('admin.users.edit', $u) }}" class="px-2">Edit</a>
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Delete user?');">
                            @csrf @method('DELETE')
                            <button class="px-2 text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection