<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

/**
 * Registers application-wide services and settings.
 *
 * In Lab Activity 11, this provider also helps detect
 * inefficient Eloquent relationship queries.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        /**
         * Prevent lazy loading during development.
         *
         * Lazy loading occurs when Laravel retrieves a
         * relationship only after its property is accessed.
         *
         * Example:
         * $post->author->name
         *
         * If the author was not loaded beforehand,
         * Laravel may execute an additional SQL query.
         *
         * This can cause the N+1 query problem when
         * displaying many posts.
         *
         * ! app()->isProduction() means:
         *
         * Development: Lazy loading prevention enabled.
         * Production: Lazy loading prevention disabled.
         *
         * We will use eager loading with with() and load()
         * in the upcoming controller steps.
         */
        Model::preventLazyLoading(! app()->isProduction());
    }
}
