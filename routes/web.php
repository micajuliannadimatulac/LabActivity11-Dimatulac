<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

/**
 * Homepage.
 *
 * Redirect visitors to the bulletin listing.
 */
Route::redirect('/', '/posts');

/**
 * Guest-only authentication routes.
 *
 * Logged-in users should not access the login page.
 */
Route::middleware('guest')->group(function () {

    // Display the login form.
    Route::get('/login', [
        AuthController::class,
        'showLogin',
    ])->name('login');

    // Process submitted login credentials.
    Route::post('/login', [
        AuthController::class,
        'login',
    ])->name('login.store');
});

/**
 * Authenticated-only logout route.
 *
 * POST prevents logout from being triggered
 * by simply opening a URL.
 */
Route::post('/logout', [
    AuthController::class,
    'logout',
])
    ->middleware('auth')
    ->name('logout');

/**
 * Public bulletin routes.
 *
 * Anyone can browse published announcements.
 */
Route::get('/posts', [
    PostController::class,
    'index',
])->name('posts.index');

Route::get('/posts/{id}', [
    PostController::class,
    'show',
])->name('posts.show');
