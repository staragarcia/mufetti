@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-12">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-2">Join Requests</h2>
        <p class="text-muted-foreground mb-8">Pending applications for <strong>{{ $group->name }}</strong></p>

        <div class="space-y-4">
            @forelse($requests as $req)
                <div class="bg-card border border-border p-5 rounded-lg flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-muted rounded-full flex items-center justify-center font-bold text-primary">
                            {{ strtoupper(substr($req->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-bold text-foreground">{{ $req->user->name }}</div>
                            <div class="text-xs text-muted-foreground">{{ '@'.$req->user->username }}</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <form action="{{ route('joinRequests.accept', $req->id) }}" method="POST">
                            @csrf
                            <button class="bg-green-600 text-white px-4 py-1.5 rounded-md text-sm font-bold hover:bg-green-700 transition">Approve</button>
                        </form>
                        <form action="{{ route('joinRequests.decline', $req->id) }}" method="POST">
                            @csrf
                            <button class="bg-red-50 text-red-600 px-4 py-1.5 rounded-md text-sm font-bold hover:bg-red-100 transition border border-red-100">Decline</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-muted/20 rounded-xl border border-dashed border-border">
                    <p class="text-muted-foreground">No pending requests for this group.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection