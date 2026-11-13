<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the comments table.
     *
     * Each comment belongs to one post and one user.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {

            // Primary key that uniquely identifies each comment.
            $table->id();

            // Connect each comment to an existing post.
            // If the post is deleted, its comments are deleted too.
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            // Connect each comment to the user who wrote it.
            // If the user is deleted, their comments are deleted too.
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Store the actual text written by the user.
            $table->text('body');

            // Automatically create created_at and updated_at.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     *
     * This removes the comments table during rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
