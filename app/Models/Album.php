<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'release_date',
        'musicbrainz_id',
        'avg_rating',
        'reviews_total',
    ];

    protected $casts = [
        'release_date' => 'date',
        'musicbrainz_id' => 'string',
    ];

    // Artists related to this album
    public function artists()
    {
        return $this->belongsToMany(
            Artist::class,
            'album_artists',
            'id_album',
            'id_artist'
        );
    }

    // Songs (tracks) in this album
    public function songs()
    {
        return $this->hasMany(Song::class, 'id_album');
    }

    // Reviews for this album
    public function reviews()
    {
        return $this->hasMany(AlbumReview::class, 'id_album');
    }

    // Average rating (helper)
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}

