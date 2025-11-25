{{-- resources/views/pages/posts/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl space-y-8">

        {{-- Header --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold text-foreground">Edit Post</h1>
            <p class="text-muted-foreground mt-2">Update your post to keep the conversation going.</p> 
        </div>

        {{-- Post Edit Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-medium text-foreground">Title</label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title', $post->title) }}"
                        required
                        autofocus
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('title') border-red-500 @enderror"
                        placeholder="Give your post a title..."
                    >
                    @error('title')
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
                        placeholder="Share your thoughts..."
                    >{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image URL (optional) --}}
                <div class="space-y-2">
                    <label for="img" class="block text-sm font-medium text-foreground">Image URL (optional)</label>
                    <input
                        id="img"
                        name="img"
                        type="url"
                        value="{{ old('img', $post->img) }}"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('img') border-red-500 @enderror"
                        placeholder="https://example.com/image.jpg"
                    >
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    {{-- Show current image preview if exists --}}
                    @if($post->img)
                    <div class="mt-2">
                        <p class="text-sm text-muted-foreground mb-2">Current Image:</p>
                        <img 
                            src="{{ $post->img }}" 
                            alt="Current post image" 
                            class="max-w-xs max-h-32 object-cover rounded-md border border-border"
                            onerror="this.style.display='none'"
                        >
                    </div>
                    @endif
                </div>

                {{-- Group Selection --}}
                @if(isset($groups) && $groups->count() > 0)
                <div class="space-y-2">
                    <label for="id_group" class="block text-sm font-medium text-foreground">Post to Group (optional)</label>
                    <select
                        id="id_group"
                        name="id_group"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('id_group') border-red-500 @enderror"
                    >
                        <option value="">No Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" 
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_group')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                {{-- Buttons --}}
                <div class="flex gap-4 pt-4">
                    <a 
                        href="{{ route('posts.show', $post) }}" 
                        class="flex-1 bg-muted hover:bg-muted/80 text-muted-foreground font-semibold py-2 px-4 rounded-md transition text-center"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 px-4 rounded-md transition"
                    >
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection