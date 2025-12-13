<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
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
}
