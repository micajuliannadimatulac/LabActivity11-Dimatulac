<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the views column to the existing posts table.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            // Store how many times a post has been viewed.
            // Default is zero because new posts have no views.
            $table->unsignedInteger('views')
                  ->default(0)
                  ->after('body');
        });
    }

    /**
     * Remove only the views column during rollback.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            // Reverse the change without deleting the posts table.
            $table->dropColumn('views');
        });
    }
};
