<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seed the user accounts for Barangay Bulletin.
 *
 * This creates the Kagawad account, a resident account,
 * and eight additional sample residents.
 */
class UserSeeder extends Seeder
{
    /**
     * Insert the required user accounts.
     */
    public function run(): void
    {
        // Create the Kagawad's login account.
        // updateOrCreate prevents duplicate accounts
        // when this seeder is executed more than once.
        $kagawad = User::updateOrCreate(
            [
                'email' => 'kagawad.dimatulac@bulletin.test',
            ],
            [
                'name' => 'Mica Dimatulac',
                'password' => Hash::make('bulletin2026'),
            ]
        );

        // Find the existing author profile created
        // earlier by AuthorSeeder.
        $author = Author::where(
            'email',
            'kagawad.dimatulac@bulletin.test'
        )->firstOrFail();

        // Connect the author profile to the Kagawad account.
        // The authors table stores the user_id foreign key.
        $author->update([
            'user_id' => $kagawad->id,
        ]);

        // Create a personalized resident account.
        // This user can later sign in and write comments.
        User::updateOrCreate(
            [
                'email' => 'lola.dimatulac@bulletin.test',
            ],
            [
                'name' => 'Lola Dimatulac',
                'password' => Hash::make('bulletin2026'),
            ]
        );

        // Generate eight additional sample residents.
        // UserFactory provides realistic names and emails.
        User::factory()->count(8)->create();
    }
}
