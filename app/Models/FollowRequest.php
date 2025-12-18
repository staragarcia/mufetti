<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowRequest extends Model
{
    public $timestamps = false;

    protected $table = 'follow_requests';

    protected $fillable = [
        'status',
        'id_follower',
        'id_followed',
    ];

    protected $casts = [
        'created_at' => 'date',
    ];

    /**
     * Get the user who sent the follow request
     */
    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_follower');
    }

    /**
     * Get the user who received the follow request
     */
    public function followed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_followed');
    }

    /**
     * Scope for pending requests only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Accept the follow request
     */
    public function accept()
    {
        $this->status = 'accepted';
        $this->save();

        // Add to followings table
        $this->followed->followers()->attach($this->id_follower);
    }

    /**
     * Decline the follow request
     */
    public function decline()
    {
        $this->status = 'declined';
        $this->save();
    }
}
