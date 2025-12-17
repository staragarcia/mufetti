{{-- resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl space-y-8">

        {{-- Header --}}
        <div class="text-center">
            @if ($group)
                <h1 class="text-3xl font-bold text-foreground">Create New Post to {{ $group->name }}</h1>
            @else
                <h1 class="text-3xl font-bold text-foreground">Create New Post</h1>
            @endif
            <p class="text-muted-foreground mt-2">Add your voice to the community's soundtrack.</p>
        </div>

        {{-- Post Creation Card --}}
        <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Title --}}
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-medium text-foreground">Title</label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
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
                        placeholder="tba"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image Upload (optional) --}}
                <div class="space-y-3">
                    <label for="post-img" class="block text-sm font-medium text-foreground">Upload Image (optional)</label>
                    
                    {{-- Preview Area --}}
                    <div id="create-post-preview-container" class="hidden">
                        <div class="relative rounded-lg overflow-hidden border-2 border-border max-w-md">
                            <img 
                                id="create-post-preview-image" 
                                src="" 
                                alt="Post preview" 
                                class="w-full h-auto max-h-64 object-cover"
                            />
                            <button 
                                type="button" 
                                onclick="clearCreatePostImage()" 
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Upload Button --}}
                    <label for="post-img" class="cursor-pointer">
                        <div class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 border-2 border-dashed border-border hover:border-primary rounded-lg transition text-sm font-medium @error('img') border-red-500 @enderror">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Choose an image for your post</span>
                        </div>
                    </label>
                    <input
                        id="post-img"
                        name="img"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        onchange="loadCreatePostPreview(event)"
                    >
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-muted-foreground">Supported formats: JPG, PNG, GIF (max 2MB)</p>
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
                        document.getElementById('post-img').value = '';
                        document.getElementById('create-post-preview-container').classList.add('hidden');
                        document.getElementById('create-post-preview-image').src = '';
                    }
                </script>

                {{-- Post to Group (optional) --}}
                @if (isset($group))
                    <input type="hidden" id="id_group" name="id_group" value="{{ $group->id }}"
                @endif

                {{-- Submit Button --}}
                <div class="flex gap-4 pt-4">
                    <a
                        href="javascript:history.back()"
                        class="flex-1 bg-muted hover:bg-muted/80 text-muted-foreground font-semibold py-2 px-4 rounded-md transition text-center"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 px-4 rounded-md transition"
                    >
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
