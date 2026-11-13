<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a user_id column to the existing authors table.
     *
     * This allows each author to be linked to a user
     * account for authentication and post ownership.
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {

            // Add a nullable user_id column.
            // Nullable keeps existing Lab 10 authors valid
            // even before they are linked to user accounts.
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            // unique() ensures one user can have
            // only one author profile.

            // nullOnDelete() means deleting a user
            // will set the author's user_id to NULL
            // instead of deleting the author.
        });
    }

    /**
     * Reverse the changes made in up().
     *
     * Laravel uses this method during rollback.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {

            // Remove the foreign key constraint first.
            $table->dropForeign(['user_id']);

            // Remove the unique constraint.
            $table->dropUnique(['user_id']);

            // Finally, remove the user_id column.
            $table->dropColumn('user_id');
        });
    }
};
