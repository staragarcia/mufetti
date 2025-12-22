@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">
    <!-- Header -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-primary mb-1 tracking-tight">Import Album from MusicBrainz</h1>
        <p class="text-muted-foreground text-base">Search for an album or artist to import it into Mufetti</p>
    </div>

    <!-- Search Bar -->
    <div class="flex flex-col sm:flex-row gap-2 items-center mb-4 max-w-2xl mx-auto">
        <div class="relative w-full">
            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-muted-foreground">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input id="search" type="text" placeholder="Search album or artist" autocomplete="off"
                class="pl-8 pr-3 py-2 w-full rounded-md border border-border bg-background text-foreground focus:ring-1 focus:ring-primary/20 focus:border-primary transition text-base shadow-none" />
        </div>
    </div>

    <!-- Results List -->
    <div id="results" class="max-w-2xl mx-auto mt-4 space-y-3"></div>
</div>

<script>
const search = document.getElementById('search');
const results = document.getElementById('results');
let timer;

function getQueryParam(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name) || '';
}

function triggerSearch() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        const q = search.value.trim();
        if (!q) {
            results.innerHTML = '';
            return;
        }
        results.innerHTML = `<div class='flex justify-center py-6'><div class='animate-spin rounded-full h-6 w-6 border-b-2 border-primary'></div></div>`;
        fetch(`/albums/import/search?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';
                if (!data.length) {
                    results.innerHTML = `<div class='text-center text-muted-foreground'>No albums found on MusicBrainz.</div>`;
                    return;
                }
                data.forEach(album => {
                    results.innerHTML += `
                        <div class="bg-white border border-border rounded-lg flex flex-col sm:flex-row justify-between items-center gap-4 p-4 hover:shadow-sm transition">
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-base text-foreground">${album.title}</div>
                                <div class="text-sm text-muted-foreground">${album.artist} ${album.date ? '(' + album.date + ')' : ''}</div>
                            </div>
                            <form method="POST" action="/albums/import" class="flex-shrink-0">
                                <input type="hidden" name="musicbrainz_id" value="${album.musicbrainz_id}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="bg-primary text-primary-foreground px-4 py-1.5 rounded-md font-medium hover:bg-primary/90 transition text-sm">
                                    Import
                                </button>
                            </form>
                        </div>
                    `;
                });
            });
    }, 350);
}

search.addEventListener('input', triggerSearch);

window.addEventListener('DOMContentLoaded', () => {
    const q = getQueryParam('q');
    if (q) {
        search.value = q;
        triggerSearch();
    }
});
</script>
@endsection
