@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-3xl font-bold text-foreground">Search</h1>
        <p class="text-muted-foreground">Find posts, comments, groups, or users.</p>

        <form action="{{ route('search.results') }}" method="GET" class="space-y-4 bg-card border border-border p-6 rounded-lg">
            @csrf

            {{-- Search input --}}
            <div>
                <label class="text-sm font-medium text-foreground">Search query</label>
                <input type="text" name="query" required
                       class="w-full mt-1 px-3 py-2 rounded-md border border-border bg-background focus:ring focus:ring-primary/50"
                       placeholder="Type something...">
            </div>

            {{-- Type selector --}}
            <div>
                <label class="text-sm font-medium text-foreground">Search for</label>
                <select name="type"
                        class="w-full mt-1 px-3 py-2 rounded-md border border-border bg-background">
                    <option value="posts">Posts</option>
                    <option value="comments">Comments</option>
                    <option value="groups">Groups</option>
                    <option value="users">Users</option>
                </select>
            </div>

            {{-- Submit button --}}
            <button type="submit"
                    class="w-full py-2 bg-primary text-primary-foreground font-semibold rounded-md hover:bg-primary/90 transition">
                Search
            </button>

        </form>

    </div>
</div>
@endsection

