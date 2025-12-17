<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use App\Services\MusicBrainzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    protected MusicBrainzService $musicBrainz;

    public function __construct(MusicBrainzService $musicBrainz)
    {
        $this->musicBrainz = $musicBrainz;
    }

    /**
     * Import album from MusicBrainz
     */
    public function import(Request $request)
    {
        $request->validate([
            'musicbrainz_id' => 'required|uuid',
        ]);

        return DB::transaction(function () use ($request) {

            // 1️⃣ Buscar dados ao MusicBrainz
            $data = $this->musicBrainz->getAlbumDetails($request->musicbrainz_id);

            // 2️⃣ Criar ou obter álbum
            $album = Album::firstOrCreate(
                ['musicbrainz_id' => $data['album']['musicbrainz_id']],
                [
                    'title' => $data['album']['title'],
                    'release_date' => $data['album']['release_date'],
                ]
            );

            // 3️⃣ Criar artistas e associar
            foreach ($data['artists'] as $artistData) {
                $artist = Artist::firstOrCreate(
                    ['musicbrainz_id' => $artistData['musicbrainz_id']],
                    ['name' => $artistData['name']]
                );

                $album->artists()->syncWithoutDetaching($artist->id);
            }

            // 4️⃣ Criar songs (se ainda não existirem)
            foreach ($data['tracks'] as $track) {
                Song::firstOrCreate(
                    ['musicbrainz_id' => $track['musicbrainz_id']],
                    [
                        'title' => $track['title'],
                        'track_number' => $track['track_number'],
                        'duration' => $track['duration'],
                        'id_album' => $album->id,
                    ]
                );
            }

            return redirect()
                ->route('groups.showUserGroups')
                ->with('success', 'Album imported successfully!');
        });
    }

    public function show(Album $album)
    {
        $album->load([
            'artists',
            'songs' => fn ($q) => $q->orderBy('track_number'),
            'reviews.user',
        ]);

        $myReview = auth()->check()
            ? $album->reviews->firstWhere('id_user', auth()->id())
            : null;

        $averageRating = $album->reviews->avg('rating');
        $otherReviews = $album->reviews->where('id_user', '!=', auth()->id());


        return view('pages.albums.show', compact('album', 'averageRating', 'myReview', 'otherReviews'));
    }

}

