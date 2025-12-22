<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'owner',         // foreign key to users table
        'description',
        'is_public',
        'member_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'member_count' => 'integer',
    ];

    /**
     * The owner of the group (User)
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner'); //here we have to use 'owner', because laravel uses user_id as default
    }

    /**
     * Users who are members of the group
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'id_group', 'id_user');
    }

    /**
     * Content (posts) posted inside this group
     */
    public function posts()
    {
        return $this->hasMany(Content::class, 'id_group')->posts();
    }

    /**
     * Groups the user is a member or owner
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('owner', $userId)
              ->orWhereHas('members', function ($memberQ) use ($userId) {
                  $memberQ->where('id_user', $userId);
              });
        });
    }

    /**
     * Groups accessible to a user (public OR member/owner)
     */
    public function scopeAccessibleTo($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        return $query->where(function($q) use ($userId) {
            $q->where('is_public', true);
            
            if ($userId) {
                $q->orWhere(function($subQ) use ($userId) {
                    $subQ->forUser($userId);
                });
            }
        });
    }

    // Groups the user owns
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('owner', $userId);
    }

    // Groups the user is a member but not owner
    public function scopeMemberOnly($query, $userId)
    {
        return $query->where('owner', '!=', $userId)
                     ->whereHas('members', fn($q) => $q->where('id_user', $userId));
    }

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class, 'id_group');
    }

}

