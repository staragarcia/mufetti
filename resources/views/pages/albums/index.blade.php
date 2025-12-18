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
                    const coverImg = album.cover_url 
                        ? `<img src="${album.cover_url}" alt="${album.title}" class="w-16 h-16 object-cover rounded" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded flex items-center justify-center\\'><svg class=\\'w-6 h-6 text-gray-400\\' fill=\\'none\\' stroke=\\'currentColor\\' viewBox=\\'0 0 24 24\\'><path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'1.5\\' d=\\'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3\\'></path></svg></div>';">`
                        : `<div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded flex items-center justify-center"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg></div>`;
                    
                    results.innerHTML += `
                        <a href="/albums/${album.id}"
                           class="border rounded-lg hover:shadow-lg transition overflow-hidden block">
                            <div class="flex gap-3 p-3">
                                <div class="flex-shrink-0">
                                    ${coverImg}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 truncate">${album.title}</h3>
                                    <p class="text-sm text-gray-600 truncate">
                                        ${album.artists.map(a => a.name).join(', ')}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-yellow-500 text-sm">★</span>
                                        <span class="text-xs text-gray-600">${album.avg_rating}</span>
                                        <span class="text-xs text-gray-400">·</span>
                                        <span class="text-xs text-gray-500">${album.reviews_total} reviews</span>
                                    </div>
                                </div>
                            </div>
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

