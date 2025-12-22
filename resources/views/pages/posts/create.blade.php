@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl space-y-8">

        <div class="text-center">
            @if ($group)
                <h1 class="text-3xl font-bold text-foreground">Create New Post to {{ $group->name }}</h1>
            @else
                <h1 class="text-3xl font-bold text-foreground">Create New Post</h1>
            @endif
            <p class="text-muted-foreground mt-2">Add your voice to the community's soundtrack.</p>
        </div>

        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            @if(auth()->user()->is_blocked)
                <div class="text-center py-10 space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-500 rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-foreground">Account Restricted</h2>
                    <p class="text-muted-foreground">You are currently blocked from creating new content.</p>
                    
                    <div class="pt-6">
                        <a href="javascript:history.back()" class="bg-primary text-primary-foreground font-semibold py-2 px-6 rounded-md transition hover:bg-primary/90">
                            Go Back
                        </a>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-medium text-foreground">Title</label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            required
                            class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('title') border-red-500 @enderror"
                            placeholder="Give your post a title..."
                        >
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-foreground">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            required
                            class="w-full rounded-md border border-border bg-background px-3 py-2 focus:outline-none focus:ring focus:ring-primary/50 @error('description') border-red-500 @enderror"
                            placeholder="Tell the community about it..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-foreground">Upload Image (optional)</label>
                        
                        <div id="create-post-preview-container" class="hidden mb-3">
                            <div class="relative rounded-lg overflow-hidden border-2 border-border max-w-md">
                                <img id="create-post-preview-image" src="" alt="Post preview" class="w-full h-auto max-h-64 object-cover"/>
                                <button type="button" onclick="clearCreatePostImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 shadow-lg hover:bg-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <label for="post-img" class="cursor-pointer block">
                            <div class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 border-2 border-dashed border-border hover:border-primary rounded-lg transition text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Choose image</span>
                            </div>
                        </label>
                        <input id="post-img" name="img" type="file" accept="image/*" class="hidden" onchange="loadCreatePostPreview(event)">
                    </div>

                    @if (isset($group))
                        <input type="hidden" name="id_group" value="{{ $group->id }}">
                    @endif

                    <div class="flex gap-4 pt-4">
                        <a href="javascript:history.back()" class="flex-1 bg-muted hover:bg-muted/80 text-muted-foreground font-semibold py-2 px-4 rounded-md transition text-center">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 px-4 rounded-md transition">
                            Create Post
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    function loadCreatePostPreview(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('create-post-preview-image').src = e.target.result;
                document.getElementById('create-post-preview-container').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
    
    function clearCreatePostImage() {
        const input = document.getElementById('post-img');
        input.value = '';
        document.getElementById('create-post-preview-container').classList.add('hidden');
    }
</script>
@endsection