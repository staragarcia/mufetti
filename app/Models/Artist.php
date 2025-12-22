<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'musicbrainz_id',
        'country',
        'description',
    ];

    protected $casts = [
        'musicbrainz_id' => 'string',
    ];

    // Albums where this artist participated
    public function albums()
    {
        return $this->belongsToMany(
            Album::class,
            'album_artists',
            'id_artist',
            'id_album'
        );
    }
}

