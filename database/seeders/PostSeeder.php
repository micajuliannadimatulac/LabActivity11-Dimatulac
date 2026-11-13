<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Create published announcements, drafts,
     * and the personalized pinned birthday post.
     */
    public function run(): void
    {
        $authors = Author::all();

        // Forty published sample announcements.
        Post::factory()
            ->count(40)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'author_id' => $authors->random()->id,
                ]
            ))
            ->create();

        // Ten unpublished drafts.
        Post::factory()
            ->draft()
            ->count(10)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'author_id' => $authors->random()->id,
                ]
            ))
            ->create();

        // Retrieve the personalized Kagawad.
        $kagawad = Author::where(
            'email',
            'kagawad.dimatulac@bulletin.test'
        )->firstOrFail();

        // Preserve the personalized birthday announcement.
        Post::create([
            'author_id' => $kagawad->id,
            'title' => 'Happy Birthday, Mica Dimatulac!',
            'category' => 'Events',
            'body' => 'The Purok Dimatulac community wishes '
                . 'Kagawad Mica Dimatulac a happy birthday '
                . 'on August 7, 2026. Thank you for your '
                . 'dedication and service to our community.',
            'is_published' => true,
            'is_pinned' => true,
            'published_at' => '2026-08-07 08:00:00',
            'views' => 0,
        ]);
    }
}
