<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;


    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'reportable_id',
        'reportable_type',
        'motive',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reportable()
    {
        return $this->morphTo();
    }
}
