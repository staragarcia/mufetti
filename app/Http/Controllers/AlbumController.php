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

    public function index()
    {
        return view('pages.albums.index');
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'relevance');

        $query = Album::query();

        if ($q && strlen($q) >= 2) {
            $query->where(function ($sub) use ($q) {

                // Full-text search (mantido como está)
                $sub->fullTextSearch($q)

                    // Artist name
                    ->orWhereHas('artists', function ($a) use ($q) {
                        $a->where('name', 'ILIKE', "%{$q}%");
                    })

                    // Song title
                    ->orWhereHas('songs', function ($s) use ($q) {
                        $s->where('title', 'ILIKE', "%{$q}%");
                    });
            });
        }

        // sorting
        switch ($sort) {
        case 'rating':
            $query->orderByDesc('avg_rating')
                  ->orderByDesc('reviews_total');
            break;

        case 'reviews':
            $query->orderByDesc('reviews_total')
                  ->orderByDesc('avg_rating');
            break;

        default:
            // if there's a search query, sort by relevance (rank)
            if ($q && strlen($q) >= 2) {
                $query->orderByDesc('rank');
            } else {
                $query->orderByDesc('avg_rating')
                      ->orderByDesc('reviews_total');
            }
            break;
        }

        $albums = $query
            ->with(['artists:id,name'])
            ->limit(20)
            ->get();

        return response()->json(
            $albums->map(function ($album) {
                return [
                    'id' => $album->id,
                    'title' => $album->title,
                    'cover_url' => $album->cover_url,
                    'avg_rating' => number_format($album->avg_rating ?? 0, 1),
                    'reviews_total' => $album->reviews_total ?? 0,
                    'release_date' => $album->release_date?->format('Y'),
                    'artists' => $album->artists->map(fn ($a) => [
                        'id' => $a->id,
                        'name' => $a->name,
                    ]),
                    'relevance' => isset($album->rank)
                    ? round($album->rank, 4)
                    : null,
                ];
            })
        );
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

    public function import(Request $request)
    {
        $request->validate([
            'musicbrainz_id' => 'required|uuid',
        ]);

        return DB::transaction(function () use ($request) {
            $data = $this->musicBrainz->getAlbumDetails($request->musicbrainz_id);

            $album = Album::firstOrCreate(
                ['musicbrainz_id' => $data['album']['musicbrainz_id']],
                [
                    'title' => $data['album']['title'],
                    'release_date' => $data['album']['release_date'] ?: null,
                    'cover_url' => $data['album']['cover_url'] ?? null,
                ]
            );

            if (!$album->cover_url && isset($data['album']['cover_url'])) {
                $album->update(['cover_url' => $data['album']['cover_url']]);
            }

            foreach ($data['artists'] as $artistData) {
                $artist = Artist::firstOrCreate(
                    ['musicbrainz_id' => $artistData['musicbrainz_id']],
                    ['name' => $artistData['name']]
                );

                $album->artists()->syncWithoutDetaching($artist->id);
            }

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
                ->route('albums.index')
                ->with('success', 'Album imported successfully!');
        });
    }

    public function searchImport(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $results = $this->musicBrainz->searchAlbums($request->q, 5);

        return response()->json(
            collect($results)->map(fn($r) => [
                'musicbrainz_id' => $r['musicbrainz_id'],
                'title' => $r['title'],
                'artist' => collect($r['artists'])->pluck('name')->join(', '),
                'date' => $r['release_date'],
            ])->toArray()
        );
    }

    public function showImportForm()
    {
        return view('pages.albums.import');
    }
}
