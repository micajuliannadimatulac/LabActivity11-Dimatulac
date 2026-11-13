<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PostSeeder extends Seeder
{
    /**
     * Insert published posts, drafts, and one pinned post.
     */
    public function run(): void
    {
        // Retrieve the five authors created by AuthorSeeder.
        $authors = Author::all();

        // Create 40 published posts using existing authors.
        Post::factory()
            ->count(40)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'author_id' => $authors->random()->id,
                ]
            ))
            ->create();

        // Create 10 unpublished draft posts.
        Post::factory()
            ->draft()
            ->count(10)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'author_id' => $authors->random()->id,
                ]
            ))
            ->create();

        // Find the personalized Kagawad.
        $kagawad = Author::where(
            'email',
            'kagawad.dimatulac@bulletin.test'
        )->firstOrFail();

        // Create the required personalized pinned post.
        Post::create([
            'author_id' => $kagawad->id,
            'title' => 'Purok Dimatulac Clean-up Drive',
            'category' => 'Events',

            'body' => 'Residents of Purok Dimatulac are invited '
                . 'to participate in our community clean-up drive. '
                . 'Let us work together to keep our surroundings '
                . 'clean, safe, and healthy.',

            // This announcement is publicly visible.
            'is_published' => true,

            // Pinned posts appear before regular posts.
            'is_pinned' => true,

            // Personalized birthday date.
            'published_at' => '2026-08-07 08:00:00',

            // Initial number of views.
            'views' => 0,
        ]);
    }
}
