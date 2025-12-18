@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

    {{-- Album Header with Cover Art --}}
    <div class="flex gap-6 items-start">
        {{-- Album Cover --}}
        <div class="flex-shrink-0">
            @if($album->cover_url)
                <img 
                    src="{{ $album->cover_url }}" 
                    alt="{{ $album->title }} cover"
                    class="w-64 h-64 object-cover rounded-lg shadow-lg border border-gray-200"
                    onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<div class=\'w-64 h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-lg border border-gray-200 flex items-center justify-center\'><svg class=\'w-24 h-24 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3\'></path></svg></div>';"
                >
            @else
                <div class="w-64 h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-lg border border-gray-200 flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Album Info --}}
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

                {{-- Favorite Button --}}
                @auth
                    <button 
                        onclick="toggleFavorite({{ $album->id }})"
                        id="favorite-btn-{{ $album->id }}"
                        class="ml-4 p-2 rounded-full hover:bg-gray-100 transition"
                        title="{{ auth()->user()->hasFavoritedAlbum($album->id) ? 'Remove from favorites' : 'Add to favorites' }}"
                    >
                        <svg 
                            id="favorite-icon-{{ $album->id }}"
                            class="w-8 h-8 {{ auth()->user()->hasFavoritedAlbum($album->id) ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                            fill="{{ auth()->user()->hasFavoritedAlbum($album->id) ? 'currentColor' : 'none' }}"
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                @endauth
            </div>

            @if($album->release_date)
                <p class="text-sm text-gray-500 mt-1">
                    Released {{ $album->release_date->format('Y') }}
                </p>
            @endif
            
            {{-- Rating --}}
            <div class="flex items-center gap-2 mt-4">
                <span class="text-yellow-500 text-lg">★</span>
                <span class="font-semibold">
                    {{ number_format($averageRating ?? 0, 1) }}
                </span>
                <span class="text-sm text-gray-500">
                    ({{ $album->reviews->count() }} reviews)
                </span>
            </div>
        </div>
    </div>

    {{-- Tracklist --}}
    <div>
        <h2 class="text-xl font-semibold mb-3">Tracklist</h2>

        <ol class="space-y-1 text-gray-800">
            @foreach($album->songs as $song)
                <li class="flex justify-between border-b py-1">
                    <span>{{ $song->title }}</span>
                    @if($song->duration)
                        <span class="text-sm text-gray-500">
                            {{ gmdate('i:s', $song->duration) }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    {{-- Reviews --}}

    @if (!($album->reviews->count() === 1 && $myReview))
    <div>
        <h2 class="text-xl font-semibold mb-3">Reviews</h2>

        @auth
            @if ($myReview)
                <a href="#review-form" class="text-blue-600 hover:underline">
                    See your review
                </a>
            @else
                <a href="#review-form" class="text-blue-600 hover:underline">
                    Write a review
                </a>
            @endif
        @else
            <p class="text-sm text-gray-500">
                Login to write a review.
            </p>
        @endauth

        <div class="mt-4 space-y-4">
            @forelse($otherReviews as $review)
                <div class="border rounded-md p-4">
                    <div class="flex justify-between">
                        <span class="font-semibold">{{ $review->user->name }}</span>
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
            @empty
                <p class="text-gray-500">No reviews yet.</p>
            @endforelse
        </div>
    </div>
    @endif

    {{-- Review Form --}}
    @auth
    <div id="review-form" class="border-t pt-6">
        <h2 class="text-xl font-semibold mb-3">Your review</h2>

        @if ($myReview)
        <div class="border rounded-md p-4">
            <div class="flex justify-between">
                <span class="font-semibold">{{ $myReview->user->name }}</span>
                <span class="text-yellow-500">
                    {{ str_repeat('★', $myReview->rating) }}
                </span>
            </div>

            @if($myReview->review_text)
            <div class="flex justify-between">
                <p class="mt-2 text-gray-700">
                    {{ $myReview->review_text }}
                </p>
                    <button id="toggleBtn" class="px-4 py-2 rounded-md border border-border text-sm font-medium hover:bg-muted hover:bg-blue-100  transition">Editar</button>
            </div>
            @endif

        </div>

        @endif


        <form id="formsReview"  method="POST" action="{{ route('album-reviews.store', $album->id) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Rating (0–5)</label>
                <input type="number" name="rating" min="0" max="5" required
                       class="border rounded-md px-3 py-2 w-24" value="{{$myReview->rating ?? ''}}">

            </div>

            <div>
                <label class="block text-sm font-medium">Review</label>
                <textarea name="review_text" rows="4"
                          class="border rounded-md px-3 py-2 w-full"">@if ($myReview) {{ $myReview->review_text }} @endif</textarea>
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit Review
            </button>
        </form>
    </div>

    @endauth

</div>

<script>
    const f = document.getElementById('formsReview');
    // Passa null se $myReview não existir
    const myReviewRating = @json($myReview->rating ?? null);

    if (myReviewRating !== null) {
        f.style.display = 'none';
    }
    document.getElementById('toggleBtn').addEventListener('click', function () {

        if (f.style.display === 'none') {
            f.style.display = 'block';
        } else {
            f.style.display = 'none';
        }
    });

    // Toggle favorite album
    function toggleFavorite(albumId) {
        fetch(`/favourites/albums/${albumId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok && response.status === 400) {
                return response.json().then(data => {
                    throw new Error(data.message);
                });
            }
            return response.json();
        })
        .then(data => {
            const icon = document.getElementById(`favorite-icon-${albumId}`);
            const btn = document.getElementById(`favorite-btn-${albumId}`);
            
            if (data.status === 'added') {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500', 'fill-current');
                icon.setAttribute('fill', 'currentColor');
                btn.setAttribute('title', 'Remove from favorites');
            } else if (data.status === 'removed') {
                icon.classList.remove('text-red-500', 'fill-current');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
                btn.setAttribute('title', 'Add to favorites');
            }
        })
        .catch(error => {
            alert(error.message || 'Error updating favorites');
            console.error('Error:', error);
        });
    }
</script>

@endsection

