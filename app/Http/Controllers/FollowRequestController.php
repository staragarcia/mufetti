<?php

namespace App\Http\Controllers;

use App\Models\FollowRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowRequestController extends Controller
{
    /**
     * Show all pending follow requests for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        
        $requests = FollowRequest::where('id_followed', $user->id)
            ->where('status', 'pending')
            ->with('follower')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.profile.requests', compact('requests'));
    }

    /**
     * Send a follow request to a private user
     */
    public function store(User $user)
    {
        $authUser = Auth::user();

        // Cannot send request to yourself
        if ($authUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Check if already following
        if ($authUser->isFollowing($user)) {
            return back()->with('error', 'You are already following this user.');
        }

        // Check if there's already a pending request
        if ($authUser->hasPendingRequestTo($user)) {
            return back()->with('info', 'You already have a pending request to this user.');
        }

        // If user is public, follow directly
        if ($user->is_public) {
            $authUser->following()->attach($user->id);
            return back()->with('success', 'You are now following ' . $user->name);
        }

        // Create follow request for private users
        FollowRequest::create([
            'id_follower' => $authUser->id,
            'id_followed' => $user->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Follow request sent to ' . $user->name);
    }

    /**
     * Accept a follow request
     */
    public function accept(FollowRequest $request)
    {
        $authUser = Auth::user();

        // Verify this request is for the authenticated user
        if ($request->id_followed !== $authUser->id) {
            abort(403, 'Unauthorized');
        }

        $request->accept();

        return back()->with('success', 'Follow request accepted!');
    }

    /**
     * Decline a follow request
     */
    public function decline(FollowRequest $request)
    {
        $authUser = Auth::user();

        // Verify this request is for the authenticated user
        if ($request->id_followed !== $authUser->id) {
            abort(403, 'Unauthorized');
        }

        $request->decline();

        return back()->with('success', 'Follow request declined.');
    }
}
