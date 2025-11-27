@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Join Requests for {{ $group->name }}</h1>

    @forelse($requests as $req)
        <div class="bg-card border border-border p-4 rounded-lg shadow-sm mb-4">
            <p class="font-semibold">{{ $req->user->name }}</p>
            <p class="text-sm text-muted-foreground">{{ $req->user->username }}</p>

            <div class="flex gap-3 mt-3">

                {{-- Accept --}}
                <form action="{{ route('joinRequests.accept', $req->id) }}" method="POST">
                    @csrf
                    <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                        Accept
                    </button>
                </form>

                {{-- Decline --}}
                <form action="{{ route('joinRequests.decline', $req->id) }}" method="POST">
                    @csrf
                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                        Decline
                    </button>
                </form>

            </div>
        </div>
    @empty
        <p class="text-muted-foreground">No pending join requests.</p>
    @endforelse

</div>
@endsection

