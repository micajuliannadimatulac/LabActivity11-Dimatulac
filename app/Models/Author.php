<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    // Allows this model to use AuthorFactory.
    use HasFactory;

    // Fields allowed when creating or updating an author.
    protected $fillable = [
        'name',
        'position',
        'email',
    ];
}
