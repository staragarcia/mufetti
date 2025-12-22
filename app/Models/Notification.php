<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'receiver',
        'actor',
        'is_read',
        'id_follow_request',
        'id_group_join_request',
        'id_comment',
        'id_reaction'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function actorUser()
    {
        return $this->belongsTo(User::class, 'actor');
    }

    public function getTimeAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function groupJoinRequest()
    {
        return $this->belongsTo(JoinRequest::class, 'id_group_join_request');
    }
    public function postComment()
    {
        return $this->belongsTo(Content::class, 'id_comment');
    }
    public function reaction()
    {
        return $this->belongsTo(Reaction::class, 'id_reaction');
    }


}
