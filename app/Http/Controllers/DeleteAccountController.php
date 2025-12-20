<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeleteAccountController extends Controller
{
    public function destroy(Request $request)
    {
        $user = $request->user();

        // 1. Verifica se pode apagar (Policy)
        $this->authorize('delete', $user);

        // 2. Transação para garantir que ou muda tudo ou nada
        DB::transaction(function () use ($user) {
            $this->anonymizeUser($user);

            // 3. Limpar dados sensíveis do utilizador
            $user->name = 'Deleted User';
            $user->username = 'deleted_' . $user->id . '_' . uniqid();
            $user->email = 'deleted_' . $user->id . '@example.com';
            $user->password = bcrypt(str()->random(32)); 
            $user->description = null;
            $user->profile_picture = null;
            $user->birth_date = null;
            $user->google_id = null; // Limpar se houver social login
            
            // Importante: NÃO usamos $user->delete() aqui, pois queremos manter o ID anónimo
            $user->save();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'A sua conta foi eliminada e os dados anonimizados.');
    }

    private function anonymizeUser(User $user)
    {
        $anonId = User::ANONYMOUS_ID;

        DB::table('notifications')->where('actor', $user->id)->update(['actor' => $anonId]);
        DB::table('notifications')->where('id_user', $user->id)->delete();

        \App\Models\Content::where('owner', $user->id)->update(['owner' => $anonId]);

        \App\Models\Reaction::where('id_user', $user->id)->update(['id_user' => $anonId]);
    }
}