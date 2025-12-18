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
                <div class="space-y-3">
                    <label for="edit-post-img" class="block text-sm font-medium text-foreground">Image (optional)</label>
                    
                    {{-- Current Image Preview --}}
                    @if($post->img)
                    <div id="current-image-container">
                        <p class="text-sm font-medium text-muted-foreground mb-2">Current Image:</p>
                        <div class="relative rounded-lg overflow-hidden border-2 border-border max-w-md mb-3">
                            <img 
                                id="current-post-image"
                                src="{{ asset('storage/' . $post->img) }}" 
                                alt="Current post image" 
                                class="w-full h-auto max-h-64 object-cover"
                                onerror="this.style.display='none'"
                            >
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_img" value="1" id="remove-img-checkbox" class="hidden" onchange="toggleRemoveImage(this)">
                                <button type="button" onclick="document.getElementById('remove-img-checkbox').click()" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 rounded-md transition text-sm font-medium text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span id="remove-img-text">Remove Image</span>
                                </button>
                            </label>
                        </div>
                    </div>
                    @endif
                    
                    {{-- New Image Preview --}}
                    <div id="edit-post-preview-container" class="hidden">
                        <p class="text-sm font-medium text-muted-foreground mb-2">New Image:</p>
                        <div class="relative rounded-lg overflow-hidden border-2 border-primary max-w-md">
                            <img 
                                id="edit-post-preview-image" 
                                src="" 
                                alt="New post preview" 
                                class="w-full h-auto max-h-64 object-cover"
                            />
                            <button 
                                type="button" 
                                onclick="clearEditPostImage()" 
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Upload Button --}}
                    <label for="edit-post-img" class="cursor-pointer">
                        <div class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 border-2 border-dashed border-border hover:border-primary rounded-lg transition text-sm font-medium @error('img') border-red-500 @enderror">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Choose a new image for your post</span>
                        </div>
                    </label>
                    <input
                        id="edit-post-img"
                        name="img"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        onchange="loadEditPostPreview(event)"
                    >
                    @error('img')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-muted-foreground">Supported formats: JPG, PNG, GIF (max 2MB)</p>
                </div>

                <script>
                    function loadEditPostPreview(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('edit-post-preview-image').src = e.target.result;
                                document.getElementById('edit-post-preview-container').classList.remove('hidden');
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                    
                    function clearEditPostImage() {
                        document.getElementById('edit-post-img').value = '';
                        document.getElementById('edit-post-preview-container').classList.add('hidden');
                        document.getElementById('edit-post-preview-image').src = '';
                    }
                    
                    function toggleRemoveImage(checkbox) {
                        const currentImage = document.getElementById('current-post-image');
                        const buttonText = document.getElementById('remove-img-text');
                        const button = buttonText.parentElement;
                        
                        if (checkbox.checked) {
                            currentImage.style.opacity = '0.3';
                            buttonText.textContent = 'Image will be removed';
                            button.classList.remove('bg-red-50', 'hover:bg-red-100', 'border-red-200', 'text-red-600');
                            button.classList.add('bg-green-50', 'hover:bg-green-100', 'border-green-200', 'text-green-600');
                        } else {
                            currentImage.style.opacity = '1';
                            buttonText.textContent = 'Remove Image';
                            button.classList.remove('bg-green-50', 'hover:bg-green-100', 'border-green-200', 'text-green-600');
                            button.classList.add('bg-red-50', 'hover:bg-red-100', 'border-red-200', 'text-red-600');
                        }
                    }
                </script>

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