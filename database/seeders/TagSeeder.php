<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Seed personalized tags and assign them
     * through the post_tag pivot relationship.
     */
    public function run(): void
    {
        $tagNames = [
            'Purok Dimatulac',
            'Urgent',
            'Libre',
            'Schedule',
            'Community',
        ];

        foreach ($tagNames as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }

        $tagIds = Tag::whereIn('name', $tagNames)
            ->pluck('id')
            ->all();

        // Assign one to three tags to each post.
        foreach (Post::all() as $post) {
            $numberOfTags = random_int(1, 3);

            $selectedTags = collect($tagIds)
                ->shuffle()
                ->take($numberOfTags)
                ->all();

            $post->tags()->sync($selectedTags);
        }

        // The pinned birthday announcement has two tags.
        $pinnedPost = Post::where(
            'title',
            'Happy Birthday, Mica Dimatulac!'
        )
            ->where('is_pinned', true)
            ->firstOrFail();

        $pinnedTagIds = Tag::whereIn('name', [
            'Purok Dimatulac',
            'Urgent',
        ])
            ->pluck('id')
            ->all();

        $pinnedPost->tags()->sync($pinnedTagIds);
    }
}