<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Redirect the homepage to the bulletin listing.
Route::redirect('/', '/posts');

// Display published posts with search and category filters.
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

// Display a single published post using its ID.
Route::get('/posts/{id}', [PostController::class, 'show'])
    ->name('posts.show');
