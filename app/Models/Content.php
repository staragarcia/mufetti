<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use Searchable;

    // Use timestamps since our schema has created_at
    public $timestamps = false; // disabled here, we'll use the default current date

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'title',
        'description',
        'img',
        'owner',      // foreign key to users
        'id_group',   // foreign key to groups
        'reply_to',   // foreign key to content (but just for comments)
    ];

    /**
     * this tells Laravel to convert created_at from a plain string to a smart date object
     * so we can easily format it or do date calculations (in our views etc)
     *
     *
     */
    protected $casts = [
        'created_at' => 'datetime', 
    ];

    /**
     * Get the user who owns this content.
     */
    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * Get the group this content belongs to.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    /**
     * Get the parent content this is replying to.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'reply_to');
    }

    /**
     * Get all replies to this content.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Content::class, 'reply_to');
    }

    /**
     * Get all reactions for this content.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, 'id_content');
    }

    /**
     * Get all reports for this content.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reportable_id')->where('reportable_type', $this->type);
    }

    /**
     * this function will help us get only the "post" rows, we can call it with just posts() when we build querys
     */
    public function scopePosts($query)
    {
        return $query->where('type', 'post');
    }

    /**
     * this function will help us get only the "comment" rows, we can call it with just comments() when we build querys
     */
    public function scopeComments($query)
    {
        return $query->where('type', 'comment');
    }

    /**
     * Filter content visible to a specific user
     * Takes into account:
     * - Owner's profile privacy (public vs private)
     * - Following relationships
     * - Group access (public groups vs member-only groups)
     */
    public function scopeVisibleTo($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        return $query->where(function($mainQ) use ($userId) {
            // Filter by owner accessibility
            $mainQ->where(function($ownerQ) use ($userId) {
                // Public profile owners
                $ownerQ->whereHas('ownerUser', function($userQ) {
                    $userQ->where('is_public', true);
                });

                // OR users that the current user follows
                if ($userId) {
                    $ownerQ->orWhereHas('ownerUser', function($userQ) use ($userId) {
                        $userQ->whereHas('followers', function($followQ) use ($userId) {
                            $followQ->where('id_user', $userId);
                        });
                    });
                }

                // OR user's own content
                if ($userId) {
                    $ownerQ->orWhere('owner', $userId);
                }
            });

            // AND filter by group access
            $mainQ->where(function($groupQ) use ($userId) {
                // Content not in any group
                $groupQ->whereNull('id_group');

                // OR content in public groups
                $groupQ->orWhereHas('group', function($grpQ) {
                    $grpQ->where('is_public', true);
                });

                // OR content in groups the user is a member/owner of
                if ($userId) {
                    $groupQ->orWhereHas('group', function($grpQ) use ($userId) {
                        $grpQ->forUser($userId);
                    });
                }
            });
        });
    }

    /**
     * Check if this content is a post.
     */
    public function isPost(): bool
    {
        return $this->type === 'post';
    }

    /**
     * Check if this content is a comment.
     */
    public function isComment(): bool
    {
        return $this->type === 'comment';
    }

    /**
     * Check if this content has been soft-deleted
     */
    public function isDeleted(): bool
    {
        return $this->description === '[This comment has been deleted]' || 
               $this->title === '[Deleted Post]';
    }
}
