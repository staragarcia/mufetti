@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-12">
    <div class="max-w-3xl mx-auto px-4">
        <a href="{{ route('admin.groups.index') }}" class="text-sm text-muted-foreground hover:text-primary flex items-center gap-1 mb-6">
            ← Back to Groups
        </a>

        <div class="bg-card border border-border rounded-xl shadow-sm p-8">
            <h2 class="text-2xl font-bold mb-6">Edit Group Settings</h2>
            
            <form action="{{ route('admin.groups.update', $group) }}" method="POST" class="space-y-6">
                @csrf 
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-foreground">Group Name</label>
                        <input type="text" name="name" value="{{ $group->name }}" class="w-full bg-background border border-border rounded-md px-4 py-2 outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-foreground">Description</label>
                        <textarea name="description" rows="4" class="w-full bg-background border border-border rounded-md px-4 py-2 outline-none focus:ring-2 focus:ring-primary/20">{{ $group->description }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-foreground">Privacy Mode</label>
                        <select name="is_public" class="w-full bg-background border border-border rounded-md px-4 py-2">
                            <option value="1" {{ $group->is_public ? 'selected' : '' }}>Public (Anyone can join)</option>
                            <option value="0" {{ !$group->is_public ? 'selected' : '' }}>Private (Requires approval)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-border flex gap-3">
                    <button type="submit" class="bg-primary text-primary-foreground px-6 py-2 rounded-md font-bold hover:bg-primary/90 transition">
                        Update Group
                    </button>
                    <a href="{{ route('admin.groups.index') }}" class="bg-muted text-muted-foreground px-6 py-2 rounded-md font-bold hover:bg-muted/80 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection