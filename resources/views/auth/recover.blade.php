@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center px-4">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Recuperar Senha</h2>
        <form method="POST" action="{{ route('recover.email') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" placeholder="Seu email" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">Enviar nova senha</button>
        </form>
    </div>
</div>
@endsection
