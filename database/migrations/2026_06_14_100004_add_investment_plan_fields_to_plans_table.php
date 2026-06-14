<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 3 — investment-plan fields for the Republic of Botswana platform,
 * added to the existing `plans` table (extends the legacy table without
 * disturbing the live ROI engine, which keeps using its own columns).
 *
 * The new spec-compliant plan builder + ReturnCalculator use ONLY these
 * columns:
 *   - asset_id          : the government asset this plan invests in
 *   - amount_type        : "fixed" (single fixed_amount) | "ranged" (min..max)
 *   - return_type        : reuses the existing column; "percentage" or
 *                          "fixed_amount" (fixed return). New rows set it too.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'asset_id')) {
                $table->unsignedBigInteger('asset_id')->nullable()->after('id');
                $table->index('asset_id');
            }
            if (!Schema::hasColumn('plans', 'amount_type')) {
                $table->enum('amount_type', ['fixed', 'ranged'])->nullable()->after('description');
            }
            if (!Schema::hasColumn('plans', 'fixed_amount')) {
                $table->decimal('fixed_amount', 20, 2)->nullable()->after('amount_type');
            }
            if (!Schema::hasColumn('plans', 'min_amount')) {
                $table->decimal('min_amount', 20, 2)->nullable()->after('fixed_amount');
            }
            if (!Schema::hasColumn('plans', 'max_amount')) {
                $table->decimal('max_amount', 20, 2)->nullable()->after('min_amount');
            }
            if (!Schema::hasColumn('plans', 'fixed_return')) {
                $table->decimal('fixed_return', 20, 2)->nullable()->after('max_amount');
            }
            if (!Schema::hasColumn('plans', 'return_percentage')) {
                $table->decimal('return_percentage', 8, 2)->nullable()->after('fixed_return');
            }
        });

        // FK to assets (set null if the asset is removed). Wrapped so the
        // migration still succeeds on drivers/states without the assets table.
        if (Schema::hasTable('assets') && Schema::hasColumn('plans', 'asset_id')) {
            try {
                Schema::table('plans', function (Blueprint $table) {
                    $table->foreign('asset_id')->references('id')->on('assets')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // FK already exists or unsupported — safe to ignore.
            }
        }
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            try {
                $table->dropForeign(['asset_id']);
            } catch (\Throwable $e) {
                // no-op
            }
            foreach ([
                'return_percentage', 'fixed_return', 'max_amount', 'min_amount',
                'fixed_amount', 'amount_type', 'asset_id',
            ] as $column) {
                if (Schema::hasColumn('plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
