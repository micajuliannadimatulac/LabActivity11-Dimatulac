<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Post represents a barangay announcement.
 *
 * Each post belongs to an author, can receive comments,
 * and can be assigned multiple tags.
 */
class Post extends Model
{
    // Allows PostFactory to generate sample announcements.
    use HasFactory;

    /**
     * Fields allowed for mass assignment.
     *
     * These are preserved from Lab Activity 10.
     */
    protected $fillable = [
        'author_id',
        'title',
        'category',
        'body',
        'is_published',
        'is_pinned',
        'published_at',
        'views',
    ];

    /**
     * Convert database values to appropriate PHP types.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * BELONGS TO RELATIONSHIP
     *
     * Every post belongs to one author.
     *
     * The posts table stores author_id.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * HAS MANY RELATIONSHIP
     *
     * One post can receive multiple comments.
     *
     * The comments table stores post_id.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * BELONGS TO MANY RELATIONSHIP
     *
     * One post can have multiple tags.
     * One tag can belong to multiple posts.
     *
     * The post_tag pivot table connects both models.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tag'
        );
    }
}
