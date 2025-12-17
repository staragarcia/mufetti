@extends('layouts.app')

@section('content')
<input id="search" type="text"
       placeholder="Search album or artist"
       class="border px-4 py-2 w-full">

<div id="results" class="mt-4 space-y-2"></div>

<script>
const search = document.getElementById('search');
const results = document.getElementById('results');
let timer;

search.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => {


        fetch(`/albums/import/search?q=${search.value}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';
                console.log(data);
                data.forEach(album => {
                    results.innerHTML += `
                        <div class="border p-3 rounded flex justify-between items-center">
                            <div>
                                <strong>${album.title}</strong><br>
                                <span class="text-sm text-gray-600">
                                    ${album.artist} (${album.date ?? '—'})
                                </span>
                            </div>

                            <form method="POST" action="/albums/import">
                                <input type="hidden" name="musicbrainz_id" value="${album.musicbrainz_id}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="bg-blue-600 text-white px-3 py-1 rounded">
                                    Import
                                </button>
                            </form>
                        </div>
                    `;
                });
            });
    }, 400);
});
</script>
@endsection
