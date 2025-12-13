@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-foreground mb-2">Followers</h1>
            <p class="text-muted-foreground">Users following {{ $user->username }} ({{ $followers->total() }})</p>
        </div>

        @if($followers->isEmpty())
            <div class="text-center py-12">
                <p class="text-muted-foreground text-lg">{{ $user->username }} has no followers yet.</p>
                @if(auth()->id() === $user->id)
                    <a href="{{ route('feed.show') }}" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                        Start posting to get followers
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($followers as $follower)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.showOther', $follower) }}" class="shrink-0">
                                <img
                                    src="{{ $follower->profile_picture ?? '/placeholder.jpg' }}"
                                    alt="{{ $follower->name }}"
                                    class="h-12 w-12 rounded-full border-2 border-gray-200 object-cover hover:border-blue-600 transition-colors"
                                />
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.showOther', $follower) }}" class="hover:text-blue-600 font-semibold text-foreground block truncate">
                                    {{ $follower->name }}
                                </a>
                                <p class="text-sm text-muted-foreground truncate">{{ '@' . $follower->username }}</p>
                                <div class="flex gap-4 mt-1 text-xs text-muted-foreground">
                                    <span><span class="font-semibold text-foreground">{{ $follower->following()->count() }}</span> Following</span>
                                    <span><span class="font-semibold text-foreground">{{ $follower->followers()->count() }}</span> Followers</span>
                                </div>
                            </div>

                            @auth
                                @if(auth()->id() !== $follower->id)
                                    <div class="flex gap-2">
                                        @if(auth()->user()->isFollowing($follower))
                                            <form action="{{ route('users.unfollow', $follower) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 text-xs font-medium whitespace-nowrap">
                                                    Unfollow
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.follow', $follower) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs font-medium whitespace-nowrap">
                                                    Follow
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Remove follower button (only shows for yoru own followers!) --}}
                                        @if(auth()->id() === $user->id)
                                            <form action="{{ route('users.removeFollower', $follower) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-xs font-medium whitespace-nowrap">
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $followers->links() }}
            </div>
        @endif

    </main>
</div>
@endsection