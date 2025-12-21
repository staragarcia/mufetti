<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;

class GroupPolicy
{
    /**
     * O método 'before' é executado antes de qualquer outra verificação.
     * Se retornar 'true', o acesso é concedido imediatamente.
     */
    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the group.
     */
    public function delete(User $user, Group $group): bool
    {
        // O utilizador pode apagar se for o dono. 
        // (A lógica de Admin já é tratada no método 'before' acima)
        return $user->id === $group->owner;
    }

    /**
     * Se tiveres um método de update, ele também herdará o bypass do admin
     */
    public function update(User $user, Group $group): bool
    {
        return $user->id === $group->owner;
    }
}