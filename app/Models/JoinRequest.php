<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    public $timestamps = false;
    protected $table = 'join_requests';

    protected $fillable = [
        'status',
        'id_group',
        'id_user',
        'created_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

