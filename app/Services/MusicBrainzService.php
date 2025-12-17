<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class MusicBrainzService
{
    private string $baseUrl = 'https://musicbrainz.org/ws/2';

    private array $headers = [
        'User-Agent' => 'Mufetti/1.0 ( up202303903@up.pt )',
        'Accept' => 'application/json',
    ];

    /**
     * Search albums (release-groups) by query
     */
    public function searchAlbums(string $query, int $limit = 5): array
    {
        $cacheKey = 'mb_search_' . md5($query);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($query, $limit) {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/release-group", [
                    'query' => $query,
                    'fmt' => 'json',
                    'limit' => $limit,
                ]);

            if (!$response->successful()) {
                throw new Exception('MusicBrainz search failed');
            }

            return collect($response->json('release-groups'))
                ->map(fn ($rg) => [
                    'musicbrainz_id' => $rg['id'],
                    'title' => $rg['title'],
                    'primary_type' => $rg['primary-type'] ?? null,
                    'release_date' => $rg['first-release-date'] ?? null,
                    'artists' => collect($rg['artist-credit'] ?? [])
                        ->pluck('artist')
                        ->map(fn ($a) => [
                            'name' => $a['name'],
                            'musicbrainz_id' => $a['id'],
                        ])
                        ->toArray(),
                ])
                ->toArray();
        });
    }

    /**
     * Get full album details including tracks
     */
    public function getAlbumDetails(string $releaseGroupId): array
    {
        $cacheKey = 'mb_album_' . $releaseGroupId;

        return Cache::remember($cacheKey, now()->addDays(1), function () use ($releaseGroupId) {
            // Get release group
            $rgResponse = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/release-group/{$releaseGroupId}", [
                    'inc' => 'artists+releases',
                    'fmt' => 'json',
                ]);

            if (!$rgResponse->successful()) {
                throw new Exception('Failed to fetch release group');
            }

            $rg = $rgResponse->json();

            // Pick first release to fetch tracks
            $releaseId = $rg['releases'][0]['id'] ?? null;

            if (!$releaseId) {
                throw new Exception('No releases found for album');
            }

            // Fetch tracks
            $releaseResponse = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/release/{$releaseId}", [
                    'inc' => 'recordings',
                    'fmt' => 'json',
                ]);

            if (!$releaseResponse->successful()) {
                throw new Exception('Failed to fetch tracks');
            }

            $release = $releaseResponse->json();

            return [
                'album' => [
                    'title' => $rg['title'],
                    'musicbrainz_id' => $rg['id'],
                    'release_date' => $rg['first-release-date'] ?? null,
                ],
                'artists' => collect($rg['artist-credit'])
                    ->pluck('artist')
                    ->map(fn ($a) => [
                        'name' => $a['name'],
                        'musicbrainz_id' => $a['id'],
                    ])
                    ->toArray(),
                'tracks' => collect($release['media'][0]['tracks'] ?? [])
                    ->map(fn ($t) => [
                        'title' => $t['title'],
                        'track_number' => (int) $t['number'],
                        'duration' => isset($t['length']) ? intval($t['length'] / 1000) : null,
                        'musicbrainz_id' => $t['id'] ?? null,
                    ])
                    ->toArray(),
            ];
        });
    }

    public function searchReleases(string $query): array
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'User-Agent' => 'Mufetti/1.0 (up202303903@up.pt)',
                'Accept' => 'application/json'
            ])
            ->get('https://musicbrainz.org/ws/2/release-group/', [
                'query' => $query,
                'fmt' => 'json',
                'limit' => 5,
            ]);

        return collect($response->json('release-groups'))->map(fn ($rg) => [
            'musicbrainz_id' => $rg['id'], // ✅ release-group ID
            'title' => $rg['title'],
            'artist' => $rg['artist-credit'][0]['name'] ?? 'Unknown',
            'first_release_date' => $rg['first-release-date'] ?? null,
        ])->toArray();
    }

}

