@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md space-y-6">

        <h2 class="text-center text-2xl font-bold">Reset Password</h2>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" required
                       class="w-full border px-3 py-2 rounded">
            </div>

            <button
                class="w-full bg-primary text-white py-2 rounded">
                Send reset link
            </button>

            @if (session('status'))
                <p class="text-green-500 text-sm mt-2">{{ session('status') }}</p>
            @endif
        </form>

    </div>
</div>
@endsection
