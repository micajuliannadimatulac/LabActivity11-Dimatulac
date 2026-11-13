<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the tags table.
     *
     * Tags are used to organize barangay announcements
     * into searchable groups.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {

            // Unique ID for each tag.
            $table->id();

            // Tag name must be unique to prevent duplicates.
            // Example: "Urgent" should only exist once.
            $table->string('name')->unique();

            // Automatically records creation and update dates.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by deleting the tags table.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
