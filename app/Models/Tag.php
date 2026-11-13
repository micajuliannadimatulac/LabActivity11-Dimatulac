<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tag represents a label used to organize
 * barangay announcements.
 *
 * Examples:
 * Purok Dimatulac, Urgent, Libre,
 * Schedule, and Community.
 */
class Tag extends Model
{
    /**
     * Allow the tag name to be mass assigned.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * BELONGS TO MANY RELATIONSHIP
     *
     * One tag can be assigned to multiple posts.
     *
     * Example:
     * The Urgent tag can be attached to several
     * announcements at the same time.
     *
     * The post_tag pivot table stores the connections.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'post_tag'
        );
    }
}
