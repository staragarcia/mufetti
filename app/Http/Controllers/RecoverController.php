<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoverPasswordMail;
use App\Models\User;

class RecoverController extends Controller
{
    public function show()
    {
        return view('auth.recover');
    }

    public function sendNewPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Gerar nova senha aleatória
        $newPassword = Str::random(8);

        // Atualizar senha no banco (hash)
        $user->password = bcrypt($newPassword);
        $user->save();

        // Enviar email
        $mailData = [
            'name' => $user->name,
            'password' => $newPassword
        ];

        Mail::to($user->email)->send(new RecoverPasswordMail($mailData));

        return redirect()->back()->with('success', 'Uma nova senha foi enviada para seu email.');
    }
}

