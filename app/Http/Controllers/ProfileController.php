<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('pages.profile.edit', [
            'user' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => "required|string|max:255|unique:users,username,$user->id",
            'email' => "required|email|max:255|unique:users,email,$user->id",
            'birth_date' => 'required|date',
            'description' => 'nullable|string',
            'is_private' => 'nullable|boolean',
            'password' => 'nullable|confirmed|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        // ⚡ FOTO NOVA → guardamos e substituímos
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $validated['profile_picture'] = '/storage/' . $path;
        } 
        // ⚡ SEM FOTO NOVA → mantemos a antiga (fix do bug)
        else {
            $validated['profile_picture'] = $user->profile_picture;
        }

        // Private checkbox → convert
        $validated['is_public'] = !$request->has('is_private');

        // Password só muda se for enviada
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Atualiza
        $user->update($validated);

        // ⚡ Redirect correto com pages.
        return redirect()
            ->route('pages.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function show(Request $request)
    {
        return view('pages.profile.show', [
            'user' => $request->user()
        ]);
    }
}
