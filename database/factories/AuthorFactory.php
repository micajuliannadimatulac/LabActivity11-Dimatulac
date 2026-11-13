<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Generates sample barangay authors.
 */
class AuthorFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Generate a random person's full name.
            'name' => fake()->name(),

            // Randomly assign a barangay position.
            'position' => fake()->randomElement([
                'Kagawad',
                'Secretary',
                'BHW',
                'Tanod',
            ]),

            // Generate a unique email address.
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
