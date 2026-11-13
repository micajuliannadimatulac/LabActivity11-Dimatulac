<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CommentController;

/**
 * BARANGAY BULLETIN - PUROK DIMATULAC
 *
 * Application routes for public browsing,
 * authentication, post creation, and comments.
 */

// Redirect the homepage to the bulletin listing.
Route::redirect('/', '/posts');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
|
| Only visitors who are not logged in can access
| the login form and submit login credentials.
|
*/

Route::middleware('guest')->group(function () {

    // Display the login page.
    Route::get('/login', [
        AuthController::class,
        'showLogin',
    ])->name('login');

    // Authenticate submitted credentials.
    Route::post('/login', [
        AuthController::class,
        'login',
    ])->name('login.store');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| These routes require an authenticated session.
| Guests are redirected to the login page.
|
*/

Route::middleware('auth')->group(function () {

    // Log out the current user.
    Route::post('/logout', [
        AuthController::class,
        'logout',
    ])->name('logout');

    // Display the form for creating a new post.
    Route::get('/posts/create', [
        PostController::class,
        'create',
    ])->name('posts.create');

    // Save a newly submitted post.
    Route::post('/posts', [
        PostController::class,
        'store',
    ])->name('posts.store');

    // Submit a comment on an existing post.
    Route::post('/posts/{post}/comments', [
        CommentController::class,
        'store',
    ])->name('comments.store');
});


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Everyone can browse published posts and view
| barangay author profiles.
|
*/

// Display the bulletin listing.
Route::get('/posts', [
    PostController::class,
    'index',
])->name('posts.index');

// Display a barangay author's profile.
// Route model binding resolves the Author model.
Route::get('/authors/{author}', [
    AuthorController::class,
    'show',
])->name('authors.show');

// Display one published post by its ID.
// Keep this after /posts/create to avoid route conflicts.
Route::get('/posts/{id}', [
    PostController::class,
    'show',
])->name('posts.show');
