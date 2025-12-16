<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumReview extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'rating',
        'review_text',
        'created_at',
        'id_album',
        'id_user',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
    ];

    // Album being reviewed
    public function album()
    {
        return $this->belongsTo(Album::class, 'id_album');
    }

    // User who made the review
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

