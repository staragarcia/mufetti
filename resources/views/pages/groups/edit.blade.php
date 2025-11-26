@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Edit Group</h1>

    <form action="{{ route('groups.update', $group->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ $group->name }}" class="input w-full">
        </div>

        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="input w-full">{{ $group->description }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Visibility</label>
            <select name="is_public" class="input w-full">
                <option value="1" {{ $group->is_public ? 'selected' : '' }}>Public</option>
                <option value="0" {{ !$group->is_public ? 'selected' : '' }}>Private</option>
            </select>
        </div>

        <button class="px-4 py-2 bg-primary text-white rounded">Save</button>
    </form>
</div>
@endsection

