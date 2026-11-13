<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the seeders in the correct order.
     */
    public function run(): void
    {
        $this->call([
            // Authors must exist before their posts.
            AuthorSeeder::class,

            // Posts reference existing authors.
            PostSeeder::class,
        ]);
    }
}
