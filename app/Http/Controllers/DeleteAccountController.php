<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DeleteAccountController extends Controller
{
    public function destroy(Request $request)
    {
        $user = $request->user();

        // 1. Autorização via Policy
        Gate::authorize('delete', $user);

        // 2. Transação para garantir que tudo corre bem
        DB::transaction(function () use ($user) {
            
            $anonId = User::ANONYMOUS_ID;

            // --- LÓGICA DE LIMPEZA (Igual à que fizeste no Admin) ---

            // 1. MÚSICA E PREFERÊNCIAS (Apagar, pois são pessoais)
            DB::table('favourite_albums')->where('id_user', $user->id)->delete();
            DB::table('favourite_genres')->where('id_user', $user->id)->delete();
            DB::table('album_reviews')->where('id_user', $user->id)->delete();

            // 2. SEGUIDORES E PEDIDOS (Apagar conexões sociais)
            DB::table('followings')->where('id_user', $user->id)->orWhere('id_following', $user->id)->delete();
            DB::table('follow_requests')->where('id_follower', $user->id)->orWhere('id_followed', $user->id)->delete();

            // 3. GRUPOS 
            DB::table('join_requests')->where('id_user', $user->id)->delete();
            DB::table('group_members')->where('id_user', $user->id)->delete();
            // Transferir a posse dos grupos para o Admin/Sistema
            DB::table('groups')->where('owner', $user->id)->update(['owner' => $anonId]);

            // 4. NOTIFICAÇÕES (Corrigindo o erro de coluna id_user -> receiver)
            // No teu Admin usaste 'receiver', por isso aqui usamos igual
            DB::table('notifications')->where('receiver', $user->id)->delete();
            DB::table('notifications')->where('actor', $user->id)->update(['actor' => $anonId]);
            
            // Desvincular Reações de notificações
            DB::table('notifications')
                ->whereIn('id_reaction', function($q) use ($user) {
                    $q->select('id')->from('reactions')->where('id_user', $user->id);
                })->update(['id_reaction' => null]);

            // 5. REAÇÕES E CONTEÚDOS
            // Nas reações, o utilizador costuma querer que o seu "Like" desapareça, 
            // mas podes optar por passar para o anonId se preferires manter a contagem.
            DB::table('reactions')->where('id_user', $user->id)->delete();
            
            // Posts e Comentários passam para o utilizador Anónimo
            DB::table('contents')->where('owner', $user->id)->update(['owner' => $anonId]);

            // 6. ANONIMIZAR O PERFIL (O utilizador permanece na DB mas sem dados)
            $user->name = 'Deleted User';
            $user->username = 'deleted_' . $user->id . '_' . uniqid();
            $user->email = 'deleted_' . $user->id . '@example.com';
            $user->password = bcrypt(str()->random(32)); 
            $user->description = null;
            $user->profile_picture = null;
            $user->birth_date = '1900-01-01';
            $user->google_id = null;
            $user->is_public = false; 
            
            $user->save();
        });

        // 3. Logout e encerrar sessão
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'A sua conta foi eliminada e os seus dados foram anonimizados.');
    }
}