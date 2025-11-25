<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    // Use timestamps since your schema has created_at
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
        'created_at' => 'date',
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
        return $this->title === '[Deleted Post]';
    }
}
