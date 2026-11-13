<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User represents a registered account.
 *
 * A user may have one author profile and can write
 * multiple comments on barangay announcements.
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Convert database attributes into appropriate types.
     *
     * Passwords are automatically hashed by Laravel.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * HAS ONE RELATIONSHIP
     *
     * One user can have one author profile.
     *
     * Example:
     * Mica Dimatulac -> Kagawad author profile
     *
     * The foreign key user_id is in the authors table.
     */
    public function author(): HasOne
    {
        return $this->hasOne(Author::class);
    }

    /**
     * HAS MANY RELATIONSHIP
     *
     * One user can write multiple comments.
     *
     * The comments table stores user_id to identify
     * which registered account wrote each comment.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
