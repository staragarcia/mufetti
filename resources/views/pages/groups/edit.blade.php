@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md space-y-8">

        {{-- Header --}}
        <div class="text-center">
            <h2 class="text-3xl font-bold text-foreground mb-2">Edit Group</h2>
            <p class="text-muted-foreground">Update your group details</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">

            {{-- Success message --}}
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('groups.update', $group->id) }}" method="POST" class="space-y-4">
                @csrf

                {{-- Group Name --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium">Group Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $group->name) }}"
                        required
                        class="w-full rounded-md border border-border bg-background px-3 py-2"
                    >
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium">Description</label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-md border border-border bg-background px-3 py-2"
                    >{{ old('description', $group->description) }}</textarea>
                </div>

                {{-- Visibility --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium">Visibility</label>
                    <select
                        name="is_public"
                        class="w-full rounded-md border border-border bg-background px-3 py-2"
                    >
                        <option value="1" {{ $group->is_public ? 'selected' : '' }}>
                            Public Group
                        </option>
                        <option value="0" {{ !$group->is_public ? 'selected' : '' }}>
                            Private Group
                        </option>
                    </select>
                </div>

                {{-- Save --}}
                <button
                    type="submit"
                    class="w-full bg-primary text-primary-foreground py-2 rounded-md hover:bg-primary/90 font-semibold"
                >
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

