@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-foreground mb-2">Following</h1>
            <p class="text-muted-foreground">Users you are following ({{ $following->total() }})</p>
        </div>

        @if($following->isEmpty())
            <div class="text-center py-12">
                <p class="text-muted-foreground text-lg">You are not following anyone yet.</p>
                <a href="{{ route('feed.show') }}" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Browse the feed to find users to follow
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($following as $followedUser)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.showOther', $followedUser) }}" class="shrink-0">
                                <img
                                    src="{{ $followedUser->profile_picture ?? '/placeholder.jpg' }}"
                                    alt="{{ $followedUser->name }}"
                                    class="h-12 w-12 rounded-full border-2 border-gray-200 object-cover hover:border-blue-600 transition-colors"
                                />
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.showOther', $followedUser) }}" class="hover:text-blue-600 font-semibold text-foreground block truncate">
                                    {{ $followedUser->name }}
                                </a>
                                <p class="text-sm text-muted-foreground truncate">{{ '@' . $followedUser->username }}</p>
                                <div class="flex gap-4 mt-1 text-xs text-muted-foreground">
                                    <span><span class="font-semibold text-foreground">{{ $followedUser->following()->count() }}</span> Following</span>
                                    <span><span class="font-semibold text-foreground">{{ $followedUser->followers()->count() }}</span> Followers</span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <form action="{{ route('users.unfollow', $followedUser) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 text-xs font-medium whitespace-nowrap">
                                        Unfollow
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $following->links() }}
            </div>
        @endif

    </main>
</div>
@endsection