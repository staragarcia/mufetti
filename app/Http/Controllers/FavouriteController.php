<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Genre;

class FavouriteController extends Controller
{
    /**
     * Toggle favorite album for authenticated user
     */
    public function toggleAlbum(Request $request, Album $album)
    {
        $user = auth()->user();

        if ($user->hasFavoritedAlbum($album->id)) {
            $user->favouriteAlbums()->detach($album->id);
            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from favorites'
            ]);
        } else {
            // Check if user already has 6 favorite albums
            if ($user->favouriteAlbums()->count() >= 6) {
                return response()->json([
                    'status' => 'limit_reached',
                    'message' => 'You can only have 6 favorite albums. Remove one to add another.'
                ], 400);
            }
            
            $user->favouriteAlbums()->attach($album->id);
            return response()->json([
                'status' => 'added',
                'message' => 'Added to favorites'
            ]);
        }
    }
}
