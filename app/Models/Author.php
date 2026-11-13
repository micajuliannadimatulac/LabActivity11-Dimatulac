<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Author represents a barangay official or contributor
 * who is allowed to publish announcements.
 */
class Author extends Model
{
    // Enables factory-based author creation.
    use HasFactory;

    /**
     * Fields allowed for mass assignment.
     *
     * user_id links an author profile to a user account.
     * It was added to the authors table in Step 3.
     */
    protected $fillable = [
        'name',
        'position',
        'email',
        'user_id',
    ];

    /**
     * BELONGS TO RELATIONSHIP
     *
     * Each author profile belongs to one user account.
     *
     * The authors table stores user_id.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * HAS MANY RELATIONSHIP
     *
     * One author can publish multiple announcements.
     *
     * The posts table stores author_id.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * HAS MANY THROUGH RELATIONSHIP
     *
     * Retrieve comments received on all posts
     * published by this author.
     *
     * Author -> Posts -> Comments
     *
     * First key: author_id in posts
     * Second key: post_id in comments
     * Third key: id in authors
     * Fourth key: id in posts
     */
    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Comment::class,
            Post::class,
            'author_id',
            'post_id',
            'id',
            'id'
        );
    }
}
