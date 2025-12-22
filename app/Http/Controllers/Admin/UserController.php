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
    
        // Prevent admin from deleting their own account in the panel
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own administrator account.');
        }
    
        \DB::transaction(function () use ($user) {
            $anonId = User::ANONYMOUS_ID; // Garante que este ID existe na DB (ex: ID 0 ou 1)
    
            // 1. MÚSICA E PREFERÊNCIAS (Tabelas Pivot)
            \DB::table('favourite_albums')->where('id_user', $user->id)->delete();
            \DB::table('favourite_genres')->where('id_user', $user->id)->delete();
            \DB::table('album_reviews')->where('id_user', $user->id)->delete();
    
            // 2. SEGUIDORES E PEDIDOS
            \DB::table('followings')->where('id_user', $user->id)->orWhere('id_following', $user->id)->delete();
            \DB::table('follow_requests')->where('id_follower', $user->id)->orWhere('id_followed', $user->id)->delete();
    
            // 3. GRUPOS (Resolve o erro groups_owner_fkey)
            \DB::table('join_requests')->where('id_user', $user->id)->delete();
            \DB::table('group_members')->where('id_user', $user->id)->delete();
            // Transferir grupos do user para o utilizador anónimo
            \DB::table('groups')->where('owner', $user->id)->update(['owner' => $anonId]);
    
            // 4. NOTIFICAÇÕES (Limpeza de referências cruzadas)
            \DB::table('notifications')->where('receiver', $user->id)->delete();
            \DB::table('notifications')->where('actor', $user->id)->update(['actor' => $anonId]);
            
            // Desvincular Reações de notificações antes de apagar as reações
            \DB::table('notifications')
                ->whereIn('id_reaction', function($q) use ($user) {
                    $q->select('id')->from('reactions')->where('id_user', $user->id);
                })->update(['id_reaction' => null]);
    
            // 5. REAÇÕES E CONTEÚDOS
            \DB::table('reactions')->where('id_user', $user->id)->delete();
            \DB::table('contents')->where('owner', $user->id)->update(['owner' => $anonId]);
    
            // 6. ELIMINAÇÃO FINAL
            $user->delete();
        });
    
        return redirect()->route('admin.users.index')->with('success', 'Utilizador e dados associados removidos com sucesso.');
    }
    public function toggleBlock(User $user)
    {
        // Impede que o admin se bloqueie a si próprio por acidente
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot block your own account.');
        }

        $user->is_blocked = !$user->is_blocked;
        $user->save();

        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        
        return back()->with('success', "User {$user->name} has been {$status}.");
    }
}