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
            <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
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

                {{-- Image Upload (optional) --}}
                <div class="space-y-2">
                    <label for="img" class="block text-sm font-medium text-foreground">Upload New Image (optional)</label>
                    <input
                        id="img"
                        name="img"
                        type="file"
                        accept="image/*"
                        class="w-full rounded-md border border-border bg-background px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 focus:outline-none focus:ring focus:ring-primary/50 @error('img') border-red-500 @enderror"
                    >
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-muted-foreground">Supported formats: JPG, PNG, GIF (max 2MB)</p>
                    
                    {{-- Show current image preview if exists --}}
                    @if($post->img)
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-muted-foreground">Current Image:</p>
                            <label class="flex items-center gap-2 text-sm text-muted-foreground">
                                <input type="checkbox" name="remove_img" value="1" class="rounded border-border">
                                Remove image
                            </label>
                        </div>
                        <img 
                            src="{{ asset('storage/' . $post->img) }}" 
                            alt="Current post image" 
                            class="max-w-xs max-h-32 object-cover rounded-md border border-border"
                            onerror="this.style.display='none'"
                        >
                    </div>
                    @endif
                </div>

                {{-- Group Selection (read-only) --}}
                <div class="space-y-2">
                    <label for="posted_to" class="block text-sm font-medium text-foreground">Posted to:</label>
                    <input
                        id="posted_to"
                        type="text"
                        value="{{ $post->id_group ? optional($post->group)->name : 'No Group' }}"
                        disabled
                        class="w-full rounded-md border border-border bg-background/50 px-3 py-2 cursor-not-allowed text-muted-foreground"
                    >
                    <p class="text-sm text-muted-foreground mt-1">Groups cannot be changed after posting.</p>
                </div>

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