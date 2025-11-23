@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl space-y-8">

        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold text-foreground">Create New Group</h1>
            <p class="text-muted-foreground mt-2">Bring people together around your interests.</p>
        </div>

        {{-- Group Creation Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            <form method="POST" action="{{ route('groups.store') }}" class="space-y-6">
                @csrf

                {{-- Name --}}
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-foreground">Group Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('name') border-red-500 @enderror"
                        placeholder="Community of Music Lovers"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-foreground">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('description') border-red-500 @enderror"
                        placeholder="Describe what this group is all about..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Public/Private Toggle --}}
                <div class="space-y-2">
                    <label for="is_public" class="block text-sm font-medium text-foreground">Group Visibility</label>
                    <select
                        id="is_public"
                        name="is_public"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('is_public') border-red-500 @enderror"
                        required
                    >
                        <option value="1" {{ old('is_public') == 1 ? 'selected' : '' }}>Public</option>
                        <option value="0" {{ old('is_public') == 0 ? 'selected' : '' }}>Private</option>
                    </select>
                    @error('is_public')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-4 pt-4">
                    <a
                        href="{{ route('groups.create') }}"
                        class="flex-1 bg-muted hover:bg-muted/80 text-muted-foreground font-semibold py-2 px-4 rounded-md transition text-center"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 px-4 rounded-md transition"
                    >
                        Create Group
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection

