<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\AlbumReview;
use Illuminate\Http\Request;

class AlbumReviewController extends Controller
{
    public function store(Request $request, Album $album)
    {
        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
            'review_text' => 'nullable|string|max:2000',
        ]);

        // 1️⃣ Criar ou atualizar a review
        AlbumReview::updateOrCreate(
            [
                'id_album' => $album->id,
                'id_user' => auth()->id(),
            ],
            [
                'rating' => $request->rating,
                'review_text' => $request->review_text,
            ]
        );

        // 2️⃣ Recalcular estatísticas do álbum
        $album->update([
            'avg_rating' => round($album->reviews()->avg('rating'), 1),
            'reviews_total' => $album->reviews()->count(),
        ]);

        // 3️⃣ Redirect
        return redirect()
            ->route('albums.show', $album->id)
            ->with('success', 'Review saved successfully!');
    }
}

