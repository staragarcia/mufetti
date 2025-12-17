@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

    {{-- Album Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $album->title }}</h1>

        <p class="text-gray-600 mt-1">
            @foreach($album->artists as $artist)
                <span>{{ $artist->name }}</span>@if(!$loop->last), @endif
            @endforeach
        </p>

        @if($album->release_date)
            <p class="text-sm text-gray-500 mt-1">
                Released {{ $album->release_date->format('Y') }}
            </p>
        @endif
    </div>

    {{-- Rating --}}
    <div class="flex items-center gap-2">
        <span class="text-yellow-500 text-lg">★</span>
        <span class="font-semibold">
            {{ number_format($averageRating ?? 0, 1) }}
        </span>
        <span class="text-sm text-gray-500">
            ({{ $album->reviews->count() }} reviews)
        </span>
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
</script>

@endsection

