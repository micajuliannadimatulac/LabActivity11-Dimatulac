<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

/**
 * Create the tags used by Barangay Bulletin.
 *
 * This seeder also connects tags to posts using
 * Laravel's belongsToMany relationship.
 */
class TagSeeder extends Seeder
{
    /**
     * Insert tags and assign them to posts.
     */
    public function run(): void
    {
        // The five required tags for Purok Dimatulac.
        $tagNames = [
            'Purok Dimatulac',
            'Urgent',
            'Libre',
            'Schedule',
            'Community',
        ];

        // Create each tag only if it does not exist.
        // firstOrCreate prevents duplicate tag names.
        foreach ($tagNames as $name) {
            Tag::firstOrCreate([
                'name' => $name,
            ]);
        }

        // Retrieve the IDs of our five tags.
        // These IDs will be stored in the post_tag pivot table.
        $tagIds = Tag::whereIn('name', $tagNames)
            ->pluck('id')
            ->all();

        // Retrieve all existing posts.
        $posts = Post::all();

        // Assign 1 to 3 random tags to each post.
        foreach ($posts as $post) {

            // Randomly choose how many tags to assign.
            $numberOfTags = random_int(1, 3);

            // Shuffle the available tag IDs and select
            // the requested number without duplicates.
            $selectedTags = collect($tagIds)
                ->shuffle()
                ->take($numberOfTags)
                ->all();

            // Sync the selected tags through Eloquent.
            // sync() replaces existing tag associations
            // and prevents duplicate pivot entries.
            $post->tags()->sync($selectedTags);
        }

        // Find the personalized pinned clean-up drive.
        $pinnedPost = Post::where(
            'title',
            'Purok Dimatulac Clean-up Drive'
        )
            ->where('is_pinned', true)
            ->firstOrFail();

        // Retrieve exactly the two required tag IDs.
        $pinnedTagIds = Tag::whereIn('name', [
            'Purok Dimatulac',
            'Urgent',
        ])
            ->pluck('id')
            ->all();

        // Override the random tags for the pinned post.
        // The pinned announcement must have exactly
        // Purok Dimatulac and Urgent.
        $pinnedPost->tags()->sync($pinnedTagIds);
    }
}
