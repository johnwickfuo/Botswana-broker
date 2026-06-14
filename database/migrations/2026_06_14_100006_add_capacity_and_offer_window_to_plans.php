<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 5 — investment limits on a plan:
 *   - capacity_amount  : total principal the plan can accept (null = unlimited)
 *   - offer_starts_at  : when the offer opens (null = already open)
 *   - offer_ends_at    : when the offer closes (null = no end)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'capacity_amount')) {
                $table->decimal('capacity_amount', 20, 2)->nullable()->after('return_percentage');
            }
            if (!Schema::hasColumn('plans', 'offer_starts_at')) {
                $table->datetime('offer_starts_at')->nullable()->after('capacity_amount');
            }
            if (!Schema::hasColumn('plans', 'offer_ends_at')) {
                $table->datetime('offer_ends_at')->nullable()->after('offer_starts_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            foreach (['offer_ends_at', 'offer_starts_at', 'capacity_amount'] as $column) {
                if (Schema::hasColumn('plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
