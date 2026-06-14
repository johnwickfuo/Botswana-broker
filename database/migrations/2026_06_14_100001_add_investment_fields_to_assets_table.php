<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 2 — repurpose the legacy broker "assets" table into the Republic of
 * Botswana investment-platform Assets table. Adds the descriptive + status
 * fields the platform needs. The unused broker "symbol" column is left in
 * place (nullable) and simply no longer used.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
            if (!Schema::hasColumn('assets', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('assets', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
