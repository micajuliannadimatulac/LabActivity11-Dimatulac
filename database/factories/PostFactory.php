<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Generates sample bulletin posts.
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Create an author automatically if none is provided.
            // Our seeder will override this with existing authors.
            'author_id' => Author::factory(),

            // Generate a title containing about six words.
            'title' => fake()->sentence(6),

            // Choose one of our five required categories.
            'category' => fake()->randomElement([
                'Announcement',
                'Health',
                'Peace & Order',
                'Ayuda',
                'Events',
            ]),

            // Generate three paragraphs of sample content.
            'body' => fake()->paragraphs(3, true),

            // Factory posts are published by default.
            'is_published' => true,

            // Generated posts are not pinned.
            'is_pinned' => false,

            // Generate a publication date within three months.
            'published_at' => fake()->dateTimeBetween(
                '-3 months',
                'now'
            ),

            // New posts begin with zero views.
            'views' => 0,
        ];
    }

    /**
     * Generate an unpublished draft post.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,

            // Drafts do not have publication dates.
            'published_at' => null,
        ]);
    }
}
