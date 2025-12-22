@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

    <div class="flex gap-6 items-start">
        <div class="flex-shrink-0">
            @if($album->cover_url)
                <img src="{{ $album->cover_url }}" alt="{{ $album->title }} cover" class="w-64 h-64 object-cover rounded-lg shadow-lg border border-gray-200">
            @else
                <div class="w-64 h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-lg border border-gray-200 flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                </div>
            @endif
        </div>

        <div class="flex-1">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $album->title }}</h1>
                    <p class="text-gray-600 mt-1">
                        @foreach($album->artists as $artist)
                            <span>{{ $artist->name }}</span>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                </div>

                @auth
                    @if(!auth()->user()->is_blocked)
                        <button onclick="toggleFavorite({{ $album->id }})" id="favorite-btn-{{ $album->id }}" class="ml-4 p-2 rounded-full hover:bg-gray-100 transition">
                            <svg id="favorite-icon-{{ $album->id }}" class="w-8 h-8 {{ auth()->user()->hasFavoritedAlbum($album->id) ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="{{ auth()->user()->hasFavoritedAlbum($album->id) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    @else
                        <div class="ml-4 p-2 opacity-30 cursor-not-allowed" title="Account Restricted">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    @endif
                @endauth
            </div>

            @if($album->release_date)
                <p class="text-sm text-gray-500 mt-1">Released {{ $album->release_date->format('Y') }}</p>
            @endif

            <div class="flex items-center gap-2 mt-4">
                <span class="text-yellow-500 text-lg">★</span>
                <span class="font-semibold">{{ number_format($averageRating ?? 0, 1) }}</span>
                <span class="text-sm text-gray-500">({{ $album->reviews->count() }} reviews)</span>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-semibold mb-3">Tracklist</h2>
        <ol class="space-y-1 text-gray-800">
            @foreach($album->songs as $song)
                <li class="flex justify-between border-b py-1">
                    <span>{{ $song->title }}</span>
                    @if($song->duration)
                        <span class="text-sm text-gray-500">{{ gmdate('i:s', $song->duration) }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    @if (!($album->reviews->count() === 1 && $myReview))
    <div>
        <h2 class="text-xl font-semibold mb-3">Reviews</h2>
        @auth
            @if(!auth()->user()->is_blocked)
                <a href="#review-form" class="text-primary hover:underline">{{ $myReview ? 'See your review' : 'Write a review' }}</a>
            @endif
        @else
            <p class="text-sm text-gray-500">Login to write a review.</p>
        @endauth

        <div class="mt-4 space-y-4">
            @forelse($otherReviews as $review)
                <div class="border rounded-md p-4">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('profile.showOther', $review->user->id) }}" class="flex items-center hover:text-primary">
                            <img src="{{ $review->user->avatar ?? 'https://via.placeholder.com/40' }}" class="h-10 w-10 rounded-full mr-4 object-cover">
                            <span class="font-semibold">{{ $review->user->name }}</span>
                        </a>
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</span>
                    </div>
                    @if($review->review_text)
                        <p class="mt-2 text-gray-700">{{ $review->review_text }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No reviews yet.</p>
            @endforelse
        </div>
    </div>
    @endif

    @auth
    <div id="review-form" class="border-t pt-6">
        <h2 class="text-xl font-semibold mb-3">Your review</h2>

        @if(auth()->user()->is_blocked)
            <div class="bg-red-50 border border-red-200 rounded-md p-6 text-center">
                <p class="text-red-600 font-medium">Your account is restricted. You cannot write or edit reviews.</p>
            </div>
        @else
            @if ($myReview)
                <div class="border rounded-md p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img src="{{ $myReview->user->avatar ?? 'https://via.placeholder.com/40' }}" class="h-10 w-10 rounded-full mr-4 object-cover">
                            <span class="font-semibold">{{ $myReview->user->name }}</span>
                        </div>
                        <span class="text-yellow-500">{{ str_repeat('★', $myReview->rating) }}</span>
                    </div>
                    <div class="flex justify-between items-start mt-2">
                        <p class="text-gray-700">{{ $myReview->review_text }}</p>
                        <button id="toggleBtn" class="px-4 py-1 rounded-md border border-border text-sm font-medium hover:bg-gray-100 transition">Edit</button>
                    </div>
                </div>
            @endif

            <form id="formsReview" method="POST" action="{{ route('album-reviews.store', $album->id) }}" class="space-y-4" style="{{ $myReview ? 'display: none;' : '' }}">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Rating (0–5)</label>
                    <input type="number" name="rating" min="0" max="5" required class="border rounded-md px-3 py-2 w-24" value="{{$myReview->rating ?? ''}}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Review</label>
                    <textarea name="review_text" rows="4" class="border rounded-md px-3 py-2 w-full">@if ($myReview){{ $myReview->review_text }}@endif</textarea>
                </div>
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-primary/90 transition font-semibold">
                    Submit Review
                </button>
            </form>
        @endif
    </div>
    @endauth
</div>

<script>
    const f = document.getElementById('formsReview');
    const toggleBtn = document.getElementById('toggleBtn');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            f.style.display = (f.style.display === 'none') ? 'block' : 'none';
        });
    }

    function toggleFavorite(albumId) {
        fetch(`/favourites/albums/${albumId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const icon = document.getElementById(`favorite-icon-${albumId}`);
            if (data.status === 'added') {
                icon.classList.add('text-red-500', 'fill-current');
                icon.setAttribute('fill', 'currentColor');
            } else {
                icon.classList.remove('text-red-500', 'fill-current');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection