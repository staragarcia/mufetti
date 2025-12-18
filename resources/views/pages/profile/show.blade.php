@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">

        <div class="relative h-48 sm:h-64 rounded-xl overflow-hidden mb-4"
            style="background-color: hsl(199,89%,48%);">
            <div class="absolute inset-0 bg-linear-to-t from-black/25 to-transparent"></div>
        </div>




        <div class="relative px-4 sm:px-6">

            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 -mt-16 sm:-mt-20 mb-6">

                <img
                    src="{{ $user->avatar }}"
                    alt="Avatar"
                    class="h-32 w-32 rounded-full border-4 border-background object-cover bg-muted"
                />

                <div class="flex-1 min-w-0">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-foreground">
                                {{ $user->name }}
                            </h1>
                            <p class="text-muted-foreground">{{ "@" . $user->username }}</p>
                        </div>

                        <div class="flex gap-2">
                            @if(auth()->check() && auth()->id() === $user->id)
                                @if(!$user->is_public)
                                    {{-- Profile Requests Button (only for private profiles) --}}
                                    @php
                                        $pendingCount = $user->followRequestsReceived()->where('status', 'pending')->count();
                                    @endphp
                                    <a href="{{ route('followRequests.index') }}"
                                       class="px-4 py-2 rounded-md border border-border text-sm font-medium hover:bg-muted transition relative">
                                        Profile Requests
                                        @if($pendingCount > 0)
                                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full animate-pulse">
                                                {{ $pendingCount }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                                
                                <a href="{{ route('pages.profile.edit') }}"
                                   class="px-4 py-2 rounded-md border border-border text-sm font-medium hover:bg-muted transition">
                                    Edit Profile
                                </a>
                            @elseif(auth()->check())
                                {{-- Follow/Unfollow/Request Button --}}
                                @if(auth()->user()->isFollowing($user))
                                    <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 text-sm font-medium hover:bg-gray-300 transition">
                                            Following
                                        </button>
                                    </form>
                                @elseif(isset($hasPendingRequest) && $hasPendingRequest)
                                    <button disabled class="px-4 py-2 rounded-md bg-yellow-100 text-yellow-800 text-sm font-medium cursor-not-allowed">
                                        Request Pending
                                    </button>
                                @else
                                    <form action="{{ route('users.follow', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                            {{ $user->is_public ? 'Follow' : 'Request to Follow' }}
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>


                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-6">

                <p class="text-foreground leading-relaxed">
                    {{ $user->description ?? 'No bio yet.' }}
                </p>

                <div class="flex flex-wrap gap-4 text-sm text-muted-foreground">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>

                        <span>Joined {{ $user->created_at?->format('F Y') ?? '2024' }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        @if($user->is_public)
                            <span class="text-green-600 font-semibold">Public Profile</span>
                        @else
                            <span class="text-red-600 font-semibold">Private Profile</span>
                        @endif
                    </div>
                </div>


                <div class="flex gap-6 text-sm">
                    <div class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">{{ $user->posts()->count() }}</span>
                        <span class="text-muted-foreground">Posts</span>
                    </div>
                    <a href="{{ route('followers.show', $user) }}" class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">{{ $user->followers()->count() }}</span>
                        <span class="text-muted-foreground">Followers</span>
                    </a>
                    <a href="{{ route('following.show', $user) }}" class="hover:underline cursor-pointer">
                        <span class="font-semibold text-foreground">{{ $user->following()->count() }}</span>
                        <span class="text-muted-foreground">Following</span>
                    </a>
                </div>
            </div>

@if($canView)
{{-- Tabs --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="flex">

        {{-- Posts Tab --}}
        <a href="{{ $user->id === auth()->id() ?  route('pages.profile.show', ['tab' => 'posts']) : route('profile.showOther', ['user' => $user->id, 'tab' => 'posts']) }}"
           class="flex-1 px-6 py-3 text-center font-medium transition-all duration-200 border-b-2
           {{ ($activeTab ?? 'posts') === 'posts'
                ? 'border-blue-500 text-blue-600 bg-blue-50'
                : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            Posts
        </a>

        {{-- Reviews Tab --}}
        <a href="{{ $user->id === auth()->id() ? route('pages.profile.show', ['tab' => 'reviews']) : route('profile.showOther', ['user' => $user->id, 'tab' => 'reviews']) }}"
           class="flex-1 px-6 py-3 text-center font-medium transition-all duration-200 border-b-2
           {{ ($activeTab ?? 'posts') === 'reviews'
                ? 'border-blue-500 text-blue-600 bg-blue-50'
                : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            Reviews
        </a>

    </div>
</div>

        @if ($activeTab === 'posts')
            @if($posts->isEmpty())
                <p class="text-muted-foreground">
                    No posts yet.
                </p>
            @else
            @include('partials.showPosts')
            @endif
        @else
            @if($reviews->isEmpty())
                <p class="text-muted-foreground">No reviews yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="border rounded-lg p-4 bg-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('albums.show', $review->album->id) }}"
                                    class="font-semibold text-blue-600 hover:underline">
                                        {{ $review->album->title }}
                                    </a>
                                    <p class="text-sm text-gray-600">
                                        {{ $review->album->artists->pluck('name')->join(', ') }}
                                    </p>
                                </div>


                                <span class="text-yellow-500">
                                    {{ str_repeat('★', $review->rating) }}
                                </span>
                            </div>

                            @if($review->review_text)
                                <p class="mt-2 text-gray-700">
                                    {{ $review->review_text }}
                                </p>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif

        @endif

        </div>
    </main>
</div>
@else
    {{-- Private Profile Message --}}
    <div class="max-w-2xl mx-auto text-center py-12">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">This Account is Private</h3>
            <p class="text-gray-600">
                Follow {{ $user->name }} to see their posts and reviews.
            </p>
        </div>
    </div>
@endif
@endsection
