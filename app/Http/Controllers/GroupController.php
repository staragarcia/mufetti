<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{

    public function showAll()
    {
        $user = auth()->id();

        $groupsOwned = Group::ownedBy($user)->get();
        $groupsNotOwned = Group::memberOnly($user)->get();

        return view('pages.groups.showAll', compact('groupsOwned', 'groupsNotOwned'));
    }

    /**
     * show the form for creating a new group
     */
    public function create()
    {
        return view('pages.groups.create');
    }

    /**
     * Store a newly created group
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'is_public'   => 'required|boolean',
        ]);

        // Add additional required data
        $validated['owner'] = auth()->id(); // FK to user
        $validated['member_count'] = 1;     // owner is the first member

        // Create the group
        $group = Group::create($validated);

        // Add the owner to the group_member pivot table (R10)
        $group->members()->attach(auth()->id());

        // Redirect back with success message
        return redirect()
            ->route('groups.show', $group->id)
            ->with('success', 'Group created successfully!');
    }

    public function showGroup(Group $group)
    {
        $userId = auth()->id();

        // Secure: user must be owner or member
        if ($group->owner !== $userId && !$group->members()->where('id_user', $userId)->exists()) {
            abort(403, 'Unauthorized');
        }

        // Get posts inside this group
        $posts = $group->posts()->latest()->get();

        return view('pages.groups.show', compact('group', 'posts'));
    }


    // add the delete group (required)
    // add the edit group
}
