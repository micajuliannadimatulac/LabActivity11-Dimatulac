<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Insert five barangay authors.
     */
    public function run(): void
    {
        // Create personalized Kagawad.
        Author::create([
            'name' => 'Mica Dimatulac',
            'position' => 'Kagawad',
            'email' => 'kagawad.dimatulac@bulletin.test',
        ]);

        // Generate four additional barangay officials.
        Author::factory()->count(4)->create();
    }
}
