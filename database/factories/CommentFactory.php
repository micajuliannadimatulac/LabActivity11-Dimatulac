<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 *
 * Generates sample comments for barangay announcements.
 */
class CommentFactory extends Factory
{
    /**
     * The model associated with this factory.
     */
    protected $model = Comment::class;

    /**
     * Define the default attributes of a comment.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Use a random existing post or create one
            // when this factory is used independently.
            'post_id' => Post::factory(),

            // Use a generated user when the factory
            // is called without an assigned commenter.
            'user_id' => User::factory(),

            // Generate a realistic comment sentence.
            'body' => fake()->sentence(),
        ];
    }
}
