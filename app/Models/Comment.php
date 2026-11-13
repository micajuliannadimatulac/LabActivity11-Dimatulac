<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Comment represents a message written by a registered
 * user on a barangay announcement.
 */
class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    /**
     * Fields allowed for mass assignment.
     *
     * post_id identifies the announcement.
     * user_id identifies the commenter.
     * body contains the comment text.
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
    ];

    /**
     * BELONGS TO RELATIONSHIP
     *
     * Every comment belongs to one post.
     *
     * The comments table stores post_id.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * BELONGS TO RELATIONSHIP
     *
     * Every comment belongs to the user who wrote it.
     *
     * The comments table stores user_id.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
