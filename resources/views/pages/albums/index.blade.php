@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">
    <!-- Header -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-primary mb-1 tracking-tight">Find Your Album</h1>
        <p class="text-muted-foreground text-base">Search by album, artist, or song</p>
    </div>

    <!-- Search Bar -->
    <div class="flex flex-col sm:flex-row gap-2 items-center mb-4">
        <div class="relative w-full">
            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-muted-foreground">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input id="search" type="text" placeholder="Search album, artist or song" autocomplete="off"
                class="pl-8 pr-3 py-1.5 w-full rounded-md border border-border bg-background text-foreground focus:ring-1 focus:ring-primary/20 focus:border-primary transition text-sm shadow-none" />
        </div>
        <select id="sort" class="w-full sm:w-40 px-2 py-1.5 rounded-md border border-border bg-background text-foreground focus:ring-1 focus:ring-primary/20 focus:border-primary transition text-sm shadow-none">
            <option value="">Sort: Default</option>
            <option value="rating">Highest rating</option>
            <option value="reviews">Most reviews</option>
        </select>
    </div>

    <!-- Results -->
    <div id="results" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>
</div>

{{-- JS --}}
<script>
const searchInput = document.getElementById('search');
const sortSelect = document.getElementById('sort');
const results = document.getElementById('results');

let timeout = null;

function showLoading() {
    results.innerHTML = `<div class='col-span-full flex justify-center py-6'><div class='animate-spin rounded-full h-6 w-6 border-b-2 border-primary'></div></div>`;
}

function showNoResults(query) {
    const importUrl = `{{ route('albums.import.form') }}?q=${encodeURIComponent(query)}`;
    results.innerHTML = `
        <div class="col-span-full text-center py-6">
            <svg class="mx-auto h-10 w-10 text-muted-foreground/50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-muted-foreground text-base mb-1">No albums found.</p>
            <a href="${importUrl}" class="inline-block mt-1 px-3 py-1.5 bg-primary text-primary-foreground rounded-md font-medium hover:bg-primary/90 transition text-sm">Import from MusicBrainz</a>
        </div>
    `;
}

function renderAlbum(album) {
    const coverImg = album.cover_url 
        ? `<img src="${album.cover_url}" alt="${escapeHtml(album.title)}" class="w-14 h-14 object-cover rounded border border-border bg-background" onerror="this.onerror=null; this.outerHTML='<div class=\\'w-14 h-14 bg-gradient-to-br from-gray-100 to-gray-200 rounded flex items-center justify-center\\'><svg class=\\'w-5 h-5 text-gray-400\\' fill=\\'none\\' stroke=\\'currentColor\\' viewBox=\\'0 0 24 24\\'><path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'1.5\\' d=\\'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3\\'></path></svg></div>'">`
        : `<div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-gray-200 rounded flex items-center justify-center"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg></div>`;

    const artistNames = album.artists.map(a => escapeHtml(a.name)).join(', ');

    return `
        <a href="/albums/${album.id}"
           class="bg-card border border-border rounded-lg hover:shadow-sm transition overflow-hidden block group p-2">
            <div class="flex gap-3 items-center">
                <div class="flex-shrink-0">
                    ${coverImg}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-base text-foreground truncate group-hover:text-primary transition">${escapeHtml(album.title)}</h3>
                    <p class="text-xs text-muted-foreground truncate">
                        ${artistNames}
                    </p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="text-yellow-400 text-sm">★</span>
                        <span class="text-xs text-foreground">${album.avg_rating}</span>
                        <span class="text-xs text-muted-foreground">·</span>
                        <span class="text-xs text-muted-foreground">${album.reviews_total} reviews</span>
                    </div>
                </div>
            </div>
        </a>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function fetchAlbums() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        const q = searchInput.value.trim();
        const sort = sortSelect.value;

        showLoading();

        const url = q 
            ? `/albums/search?q=${encodeURIComponent(q)}&sort=${sort}`
            : `/albums/search?sort=${sort}`;

        fetch(url)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                console.log('Received data:', data); // Debug
                
                results.innerHTML = '';

                if (!data || data.length === 0) {
                    showNoResults(q || 'albums');
                    return;
                }

                data.forEach(album => {
                    results.innerHTML += renderAlbum(album);
                });
            })
            .catch(error => {
                console.error('Fetch error:', error);
                results.innerHTML = `
                    <div class="col-span-full text-center py-6">
                        <p class="text-red-500">Error loading albums. Please try again.</p>
                    </div>
                `;
            });
    }, 300); // Slightly longer debounce for better UX
}

// Event listeners
searchInput.addEventListener('input', fetchAlbums);
sortSelect.addEventListener('change', fetchAlbums);

// Load albums on page load
fetchAlbums();
</script>
@endsection
