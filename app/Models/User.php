<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Searchable;
    const ANONYMOUS_ID = 1;

    // Laravel timestamps are NOT present in our SQL table
    public $timestamps = false;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'birth_date',
        'profile_picture',
        'description',
        'google_id',
        'is_public',
        'is_admin',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'date',
        'is_public' => 'boolean',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get users that this user is following
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followings',
            'id_user',
            'id_following'
        );
    }

    /**
     * Get users that are following this user
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followings',
            'id_following',
            'id_user'
        );
    }

    /**
     * Check if user is following another user
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('id_following', $user->id)->exists();
    }

    /**
     * Check if user is followed by another user
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('id_user', $user->id)->exists();
    }

    /**
     * Get the user's profile picture or default avatar
     */
    public function getAvatarAttribute(): string
    {
        return $this->profile_picture ?? '/images/avatar.jpg';
    }

    public function posts()
    {
        return $this->hasMany(Content::class, 'owner');
    }

    /**
     * Get follow requests sent to this user (pending approval)
     */
    public function followRequestsReceived(): HasMany
    {
        return $this->hasMany(FollowRequest::class, 'id_followed');
    }

    /**
     * Get follow requests sent by this user
     */
    public function followRequestsSent(): HasMany
    {
        return $this->hasMany(FollowRequest::class, 'id_follower');
    }

    /**
     * Check if user has a pending follow request from another user
     */
    public function hasPendingRequestFrom(User $user): bool
    {
        return $this->followRequestsReceived()
            ->where('id_follower', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if this user sent a pending request to another user
     */
    public function hasPendingRequestTo(User $user): bool
    {
        return $this->followRequestsSent()
            ->where('id_followed', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Get favorite albums
     */
    public function favouriteAlbums(): BelongsToMany
    {
        return $this->belongsToMany(
            Album::class,
            'favourite_albums',
            'id_user',
            'id_album'
        );
    }

    /**
     * Check if user has favorited an album
     */
    public function hasFavoritedAlbum($albumId): bool
    {
        return $this->favouriteAlbums()->where('id_album', $albumId)->exists();
    }
    public function isBlocked()
    {
        return $this->is_blocked;
    }

}
