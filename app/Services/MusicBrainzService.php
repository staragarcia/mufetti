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
                ->timeout(15)
                ->connectTimeout(10)
                ->retry(3, 1000)
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
            // Get release group with retry logic
            $rgResponse = Http::withHeaders($this->headers)
                ->timeout(15)
                ->connectTimeout(10)
                ->retry(3, 1000) // 3 attempts, 1 second between attempts
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

            // Wait 1 second before next request (MusicBrainz rate limit: 1 req/sec)
            sleep(1);

            // Fetch tracks with retry logic
            $releaseResponse = Http::withHeaders($this->headers)
                ->timeout(15)
                ->connectTimeout(10)
                ->retry(3, 1000)
                ->get("{$this->baseUrl}/release/{$releaseId}", [
                    'inc' => 'recordings',
                    'fmt' => 'json',
                ]);

            if (!$releaseResponse->successful()) {
                throw new Exception('Failed to fetch tracks');
            }

            $release = $releaseResponse->json();

            // Wait before fetching cover art
            sleep(1);

            // Try to fetch cover art
            $coverUrl = $this->getCoverArt($releaseId);

            return [
                'album' => [
                    'title' => $rg['title'],
                    'musicbrainz_id' => $rg['id'],
                    'release_date' => $rg['first-release-date'] ?? null,
                    'cover_url' => $coverUrl,
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

    /**
     * Get cover art URL from Cover Art Archive
     */
    private function getCoverArt(string $releaseId): ?string
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(10)
                ->connectTimeout(10)
                ->retry(2, 1000) // Fewer retries for cover art since it's optional
                ->get("https://coverartarchive.org/release/{$releaseId}");

            if ($response->successful()) {
                $data = $response->json();
                // Get the front cover image (500px version for better quality)
                foreach ($data['images'] ?? [] as $image) {
                    if (in_array('Front', $image['types'] ?? [])) {
                        return $image['thumbnails']['500'] ?? $image['image'] ?? null;
                    }
                }
                // If no front cover, use the first available image
                return $data['images'][0]['thumbnails']['500'] ?? $data['images'][0]['image'] ?? null;
            }
        } catch (Exception $e) {
            // Cover art not available, return null
            \Log::warning('Cover art fetch failed for release ' . $releaseId . ': ' . $e->getMessage());
            return null;
        }

        return null;
    }

}

