<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\JoinRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="M06: Groups and Membership",
 *     description="All endpoints related to group creation, management and membership."
 * )
 */
class GroupController extends Controller
{

    /**
     * @OA\Get(
     *     path="/groups",
     *     summary="Show all groups for the authenticated user",
     *     description="Displays groups that the user owns or is a member of.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Response(
     *         response=200,
     *         description="List of groups",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Group"))
     *     )
     * )
     */
    public function showUserGroups(Request $request)
    {
        $activeTab = $request->query('tab', 'owned'); // default 'owned'

        $user = auth()->id();

        $groupsOwned = Group::ownedBy($user)
            ->where('name', '!=', '[Deleted Group]')->get();
        $groupsNotOwned = Group::memberOnly($user)->where('name', '!=', '[Deleted Group]')->get();

        return view('pages.groups.showAll', compact('groupsOwned', 'groupsNotOwned', 'activeTab'));
    }

    /**
     * @OA\Get(
     *     path="/groups/create",
     *     summary="Show form to create a new group",
     *     description="Returns the UI form to create a new group.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Response(
     *         response=200,
     *         description="Create group form"
     *     )
     * )
     */
    public function create()
    {
        return view('pages.groups.create');
    }

     /**
     * @OA\Post(
     *     path="/groups",
     *     summary="Create a new group",
     *     description="Stores a new group in the database. Owner becomes first member.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name","description","is_public"},
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="is_public", type="boolean")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to created group's page"
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/groups/{group}",
     *     summary="Show a group's details",
     *     description="Displays group information, posts, members, and join request status.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Group ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Group detail page",
     *         @OA\JsonContent(ref="#/components/schemas/Group")
     *     ),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function showGroup(Group $group)
    {
        $userId = auth()->id();

        $isMember = $group->members()
                          ->where('id_user', $userId)
                          ->exists();

        $canView = $group->is_public
            || ($userId && $group->owner == $userId)
            || $isMember;

        $posts = $group->posts()->where('title', '!=', '[Deleted Post]')->latest()->get();

        // Verificar se existe join request pendente
        $hasPendingRequest = false;
        if ($userId) {
            $hasPendingRequest = JoinRequest::where('id_user', $userId)
                ->where('id_group', $group->id)
                ->where('status', 'pending')
                ->exists();
        }

        $members = [];
        if ($canView || $isMember) {
            $members = $group->members()->get();
        }


        $requests = $group->joinRequests()
                          ->where('status', 'pending')
                          ->with('user')
                          ->get();

        return view('pages.groups.show', compact(
            'group', 'posts', 'canView', 'isMember', 'hasPendingRequest', 'members', 'requests'
        ));
    }

    /**
     * @OA\Post(
     *     path="/groups/{group}/leave",
     *     summary="Leave a group",
     *     description="Allows authenticated user to leave a group they are member of. Owner cannot leave.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Group ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirect after leaving group"),
     *     @OA\Response(response=403, description="Access denied for owner")
     * )
     */
    public function leaveGroup(Group $group)
    {
        $userId = auth()->id();

        // Owner não pode sair do próprio grupo
        if ($group->owner == $userId) {
            return back()->with('error', 'Group owner cannot leave the group.');
        }

        // Remover entrada da tabela pivot
        $group->members()->detach($userId);

        // Decrementar counter
        $group->decrement('member_count');

        return redirect()->route('home')->with('success', 'You left the group.');
    }

    /**
     * @OA\Delete(
     *     path="/groups/{group}/members/{user}",
     *     summary="Remove a member from a group",
     *     description="Only group owner can remove members.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Group ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="User ID to remove",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Member removed"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function removeMember(Group $group, User $user)
    {
        // Verificar se quem está a remover é o dono
        if ($group->owner !== auth()->id()) {
            abort(403);
        }

        // Owner não pode remover-se
        if ($user->id == $group->owner) {
            return back()->with('error', 'Owner cannot be removed.');
        }

        // Remover da pivot
        $group->members()->detach($user->id);

        // Atualizar contador
        $group->decrement('member_count');

        return back()->with('success', 'Member removed.');
    }

    public function edit(Group $group)
    {
        if ($group->owner !== auth()->id()) {
            abort(403);
        }

        return view('pages.groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        // AJUSTE: Permite se for o dono OU se for um administrador
        if (auth()->id() !== $group->owner && !auth()->user()->is_admin) {
            abort(403);
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);
    
        $group->update($request->only('name', 'description', 'is_public'));
    
        // AJUSTE: Se for admin, volta para a lista de admin. Se for user, volta para o grupo.
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.groups.index')
                             ->with('success', 'Group updated successfully by Administrator.');
        }
    
        return redirect()->route('groups.show', $group->id)
                         ->with('success', 'Group updated successfully.');
    }

    /**
     * @OA\Post(
     *     path="/groups/{group}/join/public",
     *     summary="Join a public group",
     *     description="Adds authenticated user to a public group.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Group ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirect after joining")
     * )
     */
    public function joinPublicGroup(Group $group)
    {
        $userId = auth()->id();

        // Já é membro
        if ($group->members()->where('id_user', $userId)->exists()) {
            return back()->with('error', 'You are already a member.');
        }

        // Adiciona diretamente
        $group->members()->attach($userId);
        $group->increment('member_count');

        return back()->with('success', 'You joined the group!');
    }


    //----- request to join functions :), talvez passar isto depois para um joinRequestController

    /**
     * @OA\Post(
     *     path="/groups/{group}/join",
     *     summary="Request to join a private group",
     *     description="Sends join request. If group is public, redirects to joinPublicGroup.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Group ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirect after sending request or joining")
     * )
     */
    public function joinRequest(Group $group)
    {
        $userId = auth()->id();

        // Grupo público → redireciona para método correto
        if ($group->is_public) {
            return $this->joinPublicGroup($group);
        }

        // Já tem pedido pendente
        if (JoinRequest::where('id_user', $userId)
            ->where('id_group', $group->id)
            ->where('status', 'pending')
            ->exists())
        {
            return back()->with('error', 'You already sent a join request.');
        }

        // Já é membro
        if ($group->members()->where('id_user', $userId)->exists()) {
            return back()->with('error', 'You are already a member.');
        }

        // Criar pedido
        JoinRequest::create([
            'id_user' => $userId,
            'id_group' => $group->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Join request sent!');
    }


     /**
     * @OA\Post(
     *     path="/join-requests/{request}/accept",
     *     summary="Accept a join request",
     *     description="Only group owner can accept a pending join request.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="request",
     *         in="path",
     *         required=true,
     *         description="Join request ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirect after accepting request"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function acceptJoinRequest(JoinRequest $request)
    {
        $group = $request->group;

        // Verificar se quem está a aceitar é o owner
        if ($group->owner !== auth()->id()) {
            abort(403);
        }

        // Já é membro?
        $alreadyMember = $group->members()
                               ->where('id_user', $request->id_user)
                               ->exists();

        if (!$alreadyMember) {
            // Adicionar membro à tabela group_members
            $group->members()->attach($request->id_user);

            // Incrementar member_count
            $group->increment('member_count');
        }

        // Atualizar status
        $request->update(['status' => 'accepted']);

        return back()->with('success', 'User added to group!');
    }


    /**
     * @OA\Post(
     *     path="/join-requests/{request}/decline",
     *     summary="Decline a join request",
     *     description="Only group owner can decline a pending join request.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="request",
     *         in="path",
     *         required=true,
     *         description="Join request ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirect after declining request"),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function declineJoinRequest(JoinRequest $request)
    {
        $group = $request->group;

        if ($group->owner !== auth()->id()) {
            abort(403);
        }

        $request->update(['status' => 'declined']);

        return back()->with('success', 'Join request declined.');
    }


    /**
     * @OA\Get(
     *     path="/groups/{group}/requests",
     *     summary="Show pending join requests",
     *     description="Only group owner can see pending requests.",
     *     tags={"M06: Groups and Membership"},
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="Group ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Pending requests list")
     * )
     */
    public function showJoinRequests(Group $group)
    {
        if ($group->owner !== auth()->id()) {
            abort(403);
        }

        $requests = $group->joinRequests()
                          ->where('status', 'pending')
                          ->with('user')
                          ->get();

        return view('pages.groups.requests', compact('group', 'requests'));
    }


    public function destroy(Group $group)
    {
        Gate::authorize('delete', $group);
    
        // 1. Remover os membros da tabela pivô primeiro
        $group->members()->detach();
    
        // 2. Se houver posts, pedidos de adesão, etc., também tens de os tratar:
        // $group->posts()->delete(); 
        // $group->joinRequests()->delete();
    
        // 3. Agora sim, apagar o grupo
        $group->delete();
    
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.groups.index')->with('success', 'Group deleted.');
        }
    
        return redirect()->route('groups.showUserGroups');
    }


    public function showMembers(Group $group)
    {
        // Pega os membros do grupo com paginação
        $members = $group->members()->paginate(12);

        return view('pages.groups.members', [
            'group' => $group,
            'members' => $members,
        ]);
    }

    public function transferOwner(Group $group, User $user)
    {
        // ADJUSTMENT: Allows if owner OR if admin (US409)
        if (auth()->id() !== $group->owner && !auth()->user()->is_admin) {
            abort(403, 'Only the owner or an administrator can transfer group ownership.');
        }
    
        // Garantir que o novo owner é membro do grupo
        // Nota: Usei 'id_user' conforme o teu código original
        $isMember = $group->members()
            ->where('id_user', $user->id) 
            ->exists();
    
        if (!$isMember) {
            return back()->with('error', 'O utilizador selecionado não é membro deste grupo.');
        }
    
        // Transferir ownership
        $group->owner = $user->id;
        $group->save();
    
        return back()->with('success', 'Group ownership transferred successfully.');
    }
    /**
     * Exibe a lista de todos os grupos para o painel de administração.
     */
    public function adminIndex()
    {
        // Verifica novamente se é admin por segurança, embora o middleware já o faça
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        // Carrega todos os grupos com a contagem de membros e o utilizador dono (owner)
        // Usamos paginate para não sobrecarregar a página se houver muitos grupos
        $groups = \App\Models\Group::with('ownerUser')
            ->withCount('members as member_count')
            ->paginate(15);

        return view('admin.groups.index', compact('groups'));
    }

    // Método para ver requests como Admin// 2. Vista de Edição do Admin (Edit)
    public function adminEdit(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    // 3. Vista de Pedidos (Requests)
    public function adminRequests(Group $group)
    {
        // Carrega os pedidos pendentes para este grupo específico
        $requests = $group->joinRequests()->with('user')->get();
        return view('admin.groups.requests', compact('group', 'requests'));
    }
    public function adminMembers(Group $group)
    {
        // Apenas admins podem aceder a esta rota específica
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $members = $group->members()->paginate(20);
        
        return view('admin.groups.members', compact('group', 'members'));
    }

    public function deactivate(Group $group)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only admins can deactivate groups');
        }

        $group->is_active = false;
        $group->save();

        return redirect()->back()->with('success', "O grupo '{$group->name}' foi desativado com sucesso.");
    }
}
