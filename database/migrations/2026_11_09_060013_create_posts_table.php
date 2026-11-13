<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the posts table.
     * Each post belongs to an existing author.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {

            // Primary key of each bulletin post.
            $table->id();

            // Foreign key that references authors.id.
            // If an author is deleted, their posts are deleted too.
            $table->foreignId('author_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Title of the announcement.
            $table->string('title');

            // Announcement, Health, Peace & Order, Ayuda, Events.
            $table->string('category');

            // Main content of the announcement.
            $table->text('body');

            // False means the post is still a draft.
            $table->boolean('is_published')->default(false);

            // False means the post is not pinned.
            $table->boolean('is_pinned')->default(false);

            // Nullable because drafts may not be published yet.
            $table->timestamp('published_at')->nullable();

            // Laravel automatically tracks creation and updates.
            $table->timestamps();
        });
    }

    /**
     * Remove the posts table during rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
