<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Main database seeder for Barangay Bulletin.
 *
 * Runs all seeders in dependency order so that
 * foreign key relationships are valid.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            // Step 1: Create barangay officials.
            // Authors must exist before posts and
            // before linking the Kagawad's user account.
            AuthorSeeder::class,

            // Step 2: Create published posts, drafts,
            // and the personalized pinned announcement.
            // Each post references an existing author.
            PostSeeder::class,

            // Step 3: Create the Kagawad, Lola Dimatulac,
            // and eight additional resident accounts.
            // Link the Kagawad to the author profile.
            UserSeeder::class,

            // Step 4: Create five personalized tags.
            // Assign 1 to 3 tags to each post and
            // exactly two tags to the pinned post.
            TagSeeder::class,

            // Step 5: Create sample comments.
            // Comments reference existing posts and users.
            CommentSeeder::class,
        ]);
    }
}
