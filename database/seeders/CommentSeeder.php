<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Seed comments for published posts,
     * including three on the pinned birthday post.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            throw new \RuntimeException(
                'Run UserSeeder before CommentSeeder.'
            );
        }

        $lola = User::where(
            'email',
            'lola.dimatulac@bulletin.test'
        )->firstOrFail();

        $posts = Post::where('is_published', true)->get();

        foreach ($posts as $post) {
            // Prevent duplicate seeded comments.
            $post->comments()->delete();

            if (
                $post->is_pinned &&
                $post->title === 'Happy Birthday, Mica Dimatulac!'
            ) {
                // Personalized comment from Lola.
                $post->comments()->create([
                    'user_id' => $lola->id,
                    'body' => 'Happy birthday, Kagawad Mica! '
                        . 'Thank you for serving Purok Dimatulac.',
                ]);

                // Two additional resident comments.
                for ($i = 0; $i < 2; $i++) {
                    $post->comments()->create([
                        'user_id' => $users->random()->id,
                        'body' => fake()->sentence(),
                    ]);
                }

                continue;
            }

            // Zero to five comments for other public posts.
            $commentCount = random_int(0, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                $post->comments()->create([
                    'user_id' => $users->random()->id,
                    'body' => fake()->sentence(),
                ]);
            }
        }
    }
}
