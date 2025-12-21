<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Updates the teams table to use the new structure:
     * - Renames 'role' to 'position'
     * - Renames 'featured' to 'is_active'
     * - Adds 'sort_order' field for manual ordering
     */
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            // Rename columns to match new structure
            $table->renameColumn('role', 'position');
            $table->renameColumn('featured', 'is_active');

            // Add sort_order column for manual ordering
            $table->integer('sort_order')->default(0)->after('is_active');

            // Add index for performance
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            // Drop index
            $table->dropIndex(['is_active', 'sort_order']);

            // Remove sort_order column
            $table->dropColumn('sort_order');

            // Rename columns back to original
            $table->renameColumn('position', 'role');
            $table->renameColumn('is_active', 'featured');
        });
    }
};
