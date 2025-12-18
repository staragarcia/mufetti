<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\AlbumReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * @OA\Tag(
 *     name="M08: Profile",
 *     description="Endpoints to show authenticated user's profile and other users' profiles."
 * )
 */
class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('pages.profile.edit', [
            'user' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => "required|string|max:255|unique:users,username,$user->id",
            'email' => "required|email|max:255|unique:users,email,$user->id",
            'birth_date' => 'required|date',
            'description' => 'nullable|string',
            'is_private' => 'nullable|boolean',
            'password' => 'nullable|confirmed|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        // ⚡ FOTO NOVA → guardamos e substituímos
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $validated['profile_picture'] = '/storage/' . $path;
        } else {
            // SEM FOTO NOVA → manter antiga
            $validated['profile_picture'] = $user->profile_picture;
        }

        // checkbox invertido
        $wasPrivate = !$user->is_public;
        $validated['is_public'] = !$request->has('is_private');
        $isNowPublic = $validated['is_public'];

        // If changing from private to public, auto-accept all pending follow requests
        if ($wasPrivate && $isNowPublic) {
            $pendingRequests = \App\Models\FollowRequest::where('id_followed', $user->id)
                ->where('status', 'pending')
                ->get();

            foreach ($pendingRequests as $followRequest) {
                $followRequest->accept();
            }
        }

        // Atualizar password só se enviada
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('pages.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function removeProfilePicture(Request $request)
    {
        $user = $request->user();
        $user->update(['profile_picture' => null]);

        return redirect()
            ->route('pages.profile.edit')
            ->with('success', 'Profile picture removed successfully');
    }

    public function myProfile(Request $request): View
    {
        $activeTab = $request->get('tab', 'posts');

        $user = Auth::user();

        $canView = true; // o dono pode sempre ver

        $posts = Content::posts()
            ->where('owner', $user->id)
            ->where('title', '!=', '[Deleted Post]')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $reviews = AlbumReview::with('album.artists')
        ->where('id_user', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();


        return view('pages.profile.show', compact('user', 'canView', 'posts', 'reviews', 'activeTab'));
    }



    /**
     * Show a specific user's profile.
     *
     * @OA\Get(
     *     path="/users/{user}",
     *     summary="Show a user's profile",
     *     description="Displays the public profile and posts of another user if they are public.",
     *     tags={"M08: Profile"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User profile view"),
     *     @OA\Response(response=403, description="Profile is private and cannot be viewed")
     * )
     */
    public function show(User $user, Request $request): View
    {
        $authUser = Auth::user();
        
        // Check if user can view this profile
        $canView = $authUser && $authUser->can('view', $user);
        
        // For guests, check if profile is public
        if (!$authUser) {
            $canView = $user->is_public;
        }

        $activeTab = $request->get('tab', 'posts');

        $posts = collect();
        $reviews = collect();
        
        if ($canView) {
            $posts = Content::posts()
                ->where('owner', $user->id)
                ->where('title', '!=', '[Deleted Post]')
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            $reviews = AlbumReview::with('album.artists')
                ->where('id_user', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Check if there's a pending follow request
        $hasPendingRequest = false;
        if ($authUser && !$canView && !$user->is_public) {
            $hasPendingRequest = $authUser->hasPendingRequestTo($user);
        }

        return view('pages.profile.show', compact('user', 'canView', 'posts', 'reviews', 'activeTab', 'hasPendingRequest'));
    }
}
