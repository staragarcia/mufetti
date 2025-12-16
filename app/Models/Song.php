<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'track_number',
        'duration',
        'musicbrainz_id',
        'id_album',
    ];

    protected $casts = [
        'duration' => 'integer',
        'track_number' => 'integer',
        'musicbrainz_id' => 'string',
    ];

    // Album this song belongs to
    public function album()
    {
        return $this->belongsTo(Album::class, 'id_album');
    }
}

