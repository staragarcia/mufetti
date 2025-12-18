<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function ensureAdmin()
    {
        $u = auth()->user();
        if (!$u || !$u->is_admin) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $q = $request->input('q');
        $users = User::query()
            ->when($q, function ($query, $q) {
                $like = "%{$q}%";
                $query->where('name', 'ILIKE', $like)
                      ->orWhere('username', 'ILIKE', $like)
                      ->orWhere('email', 'ILIKE', $like);
            })
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function show(User $user)
    {
        $this->ensureAdmin();
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
            'is_public' => 'sometimes|boolean',
            'is_admin' => 'sometimes|boolean',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        $data['birth_date'] = $data['birth_date'] ?? now()->subYears(20)->toDateString();
        $data['password'] = !empty($data['password']) ? Hash::make($data['password']) : Hash::make('password');
        $data['is_public'] = $request->has('is_public') ? true : true; // default public
        $data['is_admin'] = $request->has('is_admin') ? true : false;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = '/storage/' . $path;
        } else {
            unset($data['profile_picture']);
        }

        $user = User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $this->ensureAdmin();
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_public' => 'sometimes|boolean',
            'is_admin' => 'sometimes|boolean',
            'description' => 'nullable|string',
            'profile_picture' => 'nullable|url',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_public'] = $request->has('is_public') ? boolval($request->input('is_public')) : $user->is_public;
        $data['is_admin'] = $request->has('is_admin') ? boolval($request->input('is_admin')) : $user->is_admin;

        $user->update($data);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated.');
    }

    public function removeProfilePicture(User $user)
    {
        $this->ensureAdmin();
        $user->update(['profile_picture' => null]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Profile picture removed successfully');
    }

    public function destroy(User $user)
    {
        $this->ensureAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}