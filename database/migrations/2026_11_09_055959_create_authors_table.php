<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the authors table.
     * This stores the barangay officials who write posts.
     */
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {

            // Primary key, automatically increments.
            $table->id();

            // Full name of the barangay official.
            $table->string('name');

            // Position, e.g., Kagawad, Secretary, or BHW.
            $table->string('position');

            // Each author must have a unique email address.
            $table->string('email')->unique();

            // Automatically adds created_at and updated_at.
            $table->timestamps();
        });
    }

    /**
     * Reverse this migration by deleting the authors table.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
