<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DeleteAccountController extends Controller
{
    public function destroy(Request $request)
    {
        $user = $request->user(); // user autenticado

        $this->authorize('delete', $user);

        $this->anonymizeUser($user);

        // Apagar dados pessoais
        $user->name = 'Deleted User';
        $user->username = 'deleted_'.$user->id;
        $user->email = 'deleted_'.$user->id.'@example.com';
        $user->password = bcrypt(str()->random(32)); 
        $user->description = null;
        $user->profile_picture = null;
        $user->birth_date = null;

        $user->save();

        auth()->logout();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    private function anonymizeUser(User $user)
    {
        $anonId = User::ANONYMOUS_ID;

        // Conteúdos
        \App\Models\Content::where('owner', $user->id)
            ->update(['owner' => $anonId]);

        // Reações
        \App\Models\Reaction::where('id_user', $user->id)
            ->update(['id_user' => $anonId]);

        // Comentários (por segurança, embora já sejam contents)
        \App\Models\Content::where('owner', $user->id)
            ->where('type', 'comment')
            ->update(['owner' => $anonId]);

    }
}
