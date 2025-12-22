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

        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'self'], 422);
        }

        if ($authUser->isFollowing($user)) {
            return response()->json(['error' => 'already_following'], 422);
        }

        if (!$user->is_public) {
            FollowRequest::firstOrCreate([
                'id_follower' => $authUser->id,
                'id_followed' => $user->id,
            ]);

            return response()->json(['status' => 'pending']);
        }

        $authUser->following()->attach($user->id);

        return response()->json(['status' => 'followed']);
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
