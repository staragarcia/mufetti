<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * show the form for creating a new post
     */
    public function create()
    {
        return view('pages.posts.create'); // create this view eventually
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'img' => 'nullable|string', // images with URL
            'id_group' => 'nullable|exists:groups,id', // group is optional
        ]);

        // Create the post
        $post = Content::create([
            'type' => 'post',
            'title' => $validated['title'],
            'description' => $validated['description'],
            'img' => $validated['img'] ?? null, 
            'owner' => Auth::id(),
            'id_group' => $validated['id_group'] ?? null,
        ]);

        return redirect()->route('profile.show') // mudar para timeline ig ??
            ->with('success', 'Post created successfully!');
    }

    public function show(Content $post)
    {
        if (!$post->isPost()) {
            abort(404, 'Post not found.');
        }

        $post->load('ownerUser', 'reactions.user');

        return view('pages.posts.show', [
            'post' => $post
        ]);
    }


    public function edit(Content $post) 
    {
        
    }

    /**
    * Soft delete 
    */
    public function destroy(Content $post)
    {
        Gate::authorize('delete', $post);

        $post->update([
            'title' => '[Deleted Post]',
            'description' => 'This post has been deleted by the user.',
            'img' => null,
        ]);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Post deleted successfully']);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Post deleted successfully!');
    }

}