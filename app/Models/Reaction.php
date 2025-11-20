<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',        // like, confetti, report
        'id_user',     // who reacted
        'id_content',  // what content was reacted to
    ];

    /**
     * this tells Laravel to convert created_at from a plain string to a smart date object
     * so we can easily format it or do date calculations (in our views etc)
     */
    protected $casts = [
        'created_at' => 'date',
    ];

    /**
     * Get the user who made this reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the content that was reacted to.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'id_content');
    }

    /**
     * Check if this reaction is a like.
     */
    public function isLike(): bool
    {
        return $this->type === 'like';
    }

    /**
     * Check if this reaction is a confetti.
     */
    public function isConfetti(): bool
    {
        return $this->type === 'confetti';
    }

    /**
     * Check if this reaction is a report.
     */
    public function isReport(): bool
    {
        return $this->type === 'report';
    }

    /**
     * Scope query to only include likes.
     */
    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    /**
     * Scope query to only include confetti reactions.
     */
    public function scopeConfetti($query)
    {
        return $query->where('type', 'confetti');
    }

    /**
     * Scope query to only include reports.
     */
    public function scopeReports($query)
    {
        return $query->where('type', 'report');
    }
}