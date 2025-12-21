<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="M07: Posts",
 *     description="All endpoints related to creating, viewing, editing and deleting posts."
 * )
 */
class PostController extends Controller
{
    /**
     * show the form for creating a new post
     *
     * @OA\Get(
     *     path="/posts/create",
     *     summary="Show form to create a new post",
     *     description="Returns the UI form to create a new post. Groups owned by user are included.",
     *     tags={"M07: Posts"},
     *     @OA\Response(
     *         response=200,
     *         description="Create post form"
     *     )
     * )
     */
    public function create(?Group $group = null)
    {
        return view('pages.posts.create', compact('group'));
    }

    /**
     * Store a newly created post in storage.
     *
     * @OA\Post(
     *     path="/posts",
     *     summary="Create a new post",
     *     description="Stores a new post in the database. Optional group association.",
     *     tags={"M07: Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title","description"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="img", type="string"),
     *                 @OA\Property(property="id_group", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after creating post")
     * )
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // file upload validation (2MB max)
            'id_group' => 'nullable|exists:groups,id', // group is optional
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('posts', 'public');
        }

        // Create the post
        $post = Content::create([
            'type' => 'post',
            'title' => $validated['title'],
            'description' => $validated['description'],
            'img' => $imagePath,
            'owner' => Auth::id(),
            'id_group' => $validated['id_group'] ?? null,
        ]);

        return redirect()->route('pages.profile.show') // mudar para timeline ig ??
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post.
     *
     * @OA\Get(
     *     path="/posts/{post}",
     *     summary="Show a post",
     *     description="Displays a single post with owner and reactions.",
     *     tags={"M07: Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Post detail"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show(Content $post)
    {
        if (!$post->isPost()) {
            abort(404, 'Post not found.');
        }

        $post->load('ownerUser', 'reactions.user', 'replies.ownerUser', 'replies.replies.ownerUser');

        return view('pages.posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified post.
     *
     * @OA\Get(
     *     path="/posts/{post}/edit",
     *     summary="Show form to edit a post",
     *     description="Only post owner can edit the post.",
     *     tags={"M07: Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Edit post form"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function edit(Content $post)
    {
        // Authorization - user can only edit their own posts
        Gate::authorize('update', $post);

        $groups = Group::where('owner', Auth::id())->get();

        return view('pages.posts.edit', [
            'post' => $post,
            'groups' => $groups
        ]);
    }

    /**
     * Update the specified post in storage.
     *
     * @OA\Put(
     *     path="/posts/{post}",
     *     summary="Update a post",
     *     description="Only post owner can update the post.",
     *     tags={"M07: Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title","description"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="img", type="string"),
     *                 @OA\Property(property="id_group", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after updating post"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function update(Request $request, Content $post)
    {
        // Authorization - user can only update their own posts
        Gate::authorize('update', $post);

        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_group' => 'nullable|exists:groups,id',
            'remove_img' => 'nullable|boolean',
        ]);

        // Handle image upload/removal
        $imagePath = $post->img; // Keep existing image by default
        
        // If removing image OR uploading a new one, delete the old image
        if (($request->has('remove_img') && $request->remove_img) || $request->hasFile('img')) {
            if ($post->img && \Storage::disk('public')->exists($post->img)) {
                \Storage::disk('public')->delete($post->img);
            }
            $imagePath = null;
        }
        
        // If uploading a new image, store it
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('posts', 'public');
        }

        // Update the post
        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'img' => $imagePath,
            'id_group' => $validated['id_group'] ?? null,
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    /**
    * Soft delete the post.
    *
    * @OA\Delete(
    *     path="/posts/{post}",
    *     summary="Delete a post",
    *     description="Soft delete a post. Only post owner can delete.",
    *     tags={"M07: Posts"},
    *     @OA\Parameter(
    *         name="post",
    *         in="path",
    *         required=true,
    *         description="Post ID",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(response=200, description="Post deleted successfully"),
    *     @OA\Response(response=403, description="Access denied")
    * )
        */
        public function adminIndex(Request $request)
        {
            // 1. Pegar os Posts (onde reply_to é null)
            $posts = Content::whereNull('reply_to')
                ->with('ownerUser') // Importante para não dar erro no @username
                ->latest()
                ->paginate(10, ['*'], 'posts_page');
        
            // 2. Pegar os Comentários (onde reply_to NÃO é null)
            $comments = Content::whereNotNull('reply_to')
                ->with('ownerUser')
                ->latest()
                ->paginate(10, ['*'], 'comments_page');
        
            // 3. Definir a tab ativa
            $activeTab = $request->input('tab', 'posts');
        
            return view('admin.content.index', compact('posts', 'comments', 'activeTab'));
        }
        public function destroy(Content $post)
        {
            // O Gate verifica se é o dono OU se é Admin
            Gate::authorize('delete', $post);
        
            $isAdmin = auth()->user()->is_admin;
        
            // Se quiseres que o post SUMA da base de dados (ou use SoftDeletes se configurado no Model)
            // Em vez de fazer ->update([...]), usamos ->delete()
            $post->delete();
        
            $msg = $isAdmin ? 'Content removed by moderation.' : 'Post deleted successfully!';
        
            // Se for Admin e estiver no painel de controlo, volta para a tab certa
            if ($isAdmin) {
                // Verifica se o post era um comentário ou um post principal para saber para que tab voltar
                $tab = $post->reply_to ? 'comments' : 'posts';
                return redirect()->route('admin.content.index', ['tab' => $tab])->with('success', $msg);
            }
        
            return redirect()->route('pages.profile.show')->with('success', $msg);
        }

}

