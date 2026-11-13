<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seed comments for published barangay announcements.
 *
 * Each published post receives 0 to 5 comments.
 * The pinned clean-up drive receives at least 3 comments,
 * including one from Lola Dimatulac.
 */
class CommentSeeder extends Seeder
{
    /**
     * Create sample comments.
     */
    public function run(): void
    {
        // Retrieve the existing registered users.
        // We use these users as comment authors.
        $users = User::all();

        // Stop if there are no users to assign.
        if ($users->isEmpty()) {
            throw new \RuntimeException(
                'Run UserSeeder before CommentSeeder.'
            );
        }

        // Retrieve the personalized resident account.
        // Lola must comment on the pinned announcement.
        $lola = User::where(
            'email',
            'lola.dimatulac@bulletin.test'
        )->firstOrFail();

        // Retrieve only published announcements.
        // Draft posts should not receive sample comments.
        $posts = Post::where('is_published', true)->get();

        foreach ($posts as $post) {

            // Remove existing comments from this post
            // so rerunning the seeder does not accumulate
            // duplicate sample comments.
            $post->comments()->delete();

            // Handle the personalized pinned post separately.
            if (
                $post->is_pinned &&
                $post->title === 'Purok Dimatulac Clean-up Drive'
            ) {
                // Lola writes a personalized comment.
                $post->comments()->create([
                    'user_id' => $lola->id,
                    'body' => 'I will join the clean-up drive. '
                        . 'Thank you for organizing this activity!',
                ]);

                // Add two more comments from existing users.
                // This guarantees at least three comments.
                for ($i = 0; $i < 2; $i++) {
                    $post->comments()->create([
                        'user_id' => $users->random()->id,
                        'body' => fake()->sentence(),
                    ]);
                }

                // Skip random comment generation for this post.
                continue;
            }

            // Randomly choose 0 to 5 comments for each
            // other published announcement.
            $commentCount = random_int(0, 5);

            for ($i = 0; $i < $commentCount; $i++) {

                // Create a comment through the relationship.
                // Laravel automatically assigns post_id.
                $post->comments()->create([
                    'user_id' => $users->random()->id,
                    'body' => fake()->sentence(),
                ]);
            }
        }
    }
}
