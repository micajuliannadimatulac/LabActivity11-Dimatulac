<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the post_tag pivot table.
     *
     * This table connects posts and tags through
     * a many-to-many relationship.
     */
    public function up(): void
    {
        Schema::create('post_tag', function (Blueprint $table) {

            // Reference the post receiving the tag.
            // Deleting the post removes its tag associations.
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            // Reference the tag assigned to the post.
            // Deleting the tag removes its post associations.
            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            // Prevent assigning the same tag to a post twice.
            // Each post_id + tag_id combination must be unique.
            $table->primary(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migration by removing the pivot table.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
