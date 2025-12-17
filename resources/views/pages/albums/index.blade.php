@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Search Albums</h1>
        <p class="text-gray-600">
            Search by album, artist or song
        </p>
    </div>

    {{-- Search input --}}
    <input id="search"
           type="text"
           placeholder="Search album, artist or song"
           class="border px-4 py-2 w-full rounded">

    {{-- Filters --}}
    <select id="sort" class="border px-2 py-2 mt-3 rounded">
        <option value="">Default</option>
        <option value="rating">Highest rating</option>
        <option value="reviews">Most reviews</option>
    </select>

    {{-- Results --}}
    <div id="results" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-6"></div>

</div>

{{-- JS --}}
<script>
const searchInput = document.getElementById('search');
const sortSelect = document.getElementById('sort');
const results = document.getElementById('results');

let timeout = null;

// debounce para não spammar requests
function fetchAlbums() {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const q = searchInput.value;
        const sort = sortSelect.value;

        fetch(`/albums/search?q=${encodeURIComponent(q)}&sort=${sort}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';

                if (data.length === 0) {
                    results.innerHTML = `<p class="text-gray-500">No albums found.</p>
                                        <a href="{{ route('albums.import.form') }}" class="text-blue-600 hover:underline">
                                                import album from musicbrainz
                                        </a>
                    `;
                    return;
                }

                data.forEach(album => {
                    results.innerHTML += `
                        <a href="/albums/${album.id}"
                           class="border p-4 rounded hover:shadow transition">
                            <h3 class="font-semibold">${album.title}</h3>
                            <p class="text-sm text-gray-600">
                                ${album.artists.map(a => a.name).join(', ')}
                            </p>
                            <p class="text-xs mt-1">
                                ⭐ ${album.avg_rating} · ${album.reviews_total} reviews
                            </p>
                        </a>
                    `;
                });
            });
    }, 300);
}

searchInput.addEventListener('input', fetchAlbums);
sortSelect.addEventListener('change', fetchAlbums);

// carregar resultados iniciais
fetchAlbums();
</script>
@endsection

