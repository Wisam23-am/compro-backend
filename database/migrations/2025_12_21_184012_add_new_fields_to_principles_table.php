<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('principles', function (Blueprint $table) {
            // Add subtitle field
            if (!Schema::hasColumn('principles', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('title');
            }

            // Add sort_order field
            if (!Schema::hasColumn('principles', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('description');
            }

            // Add is_active field
            if (!Schema::hasColumn('principles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order');
            }

            // Add soft deletes
            if (!Schema::hasColumn('principles', 'deleted_at')) {
                $table->softDeletes();
            }

            // Add indexes for performance
            $table->index('is_active');
            $table->index('sort_order');
        });

        // Make icon and image nullable
        Schema::table('principles', function (Blueprint $table) {
            $table->string('icon')->nullable()->change();
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('principles', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'sort_order', 'is_active', 'deleted_at']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['sort_order']);
        });
    }
};
