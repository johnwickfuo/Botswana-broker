<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Investments are made directly into ASSETS. The investment terms (amount
 * limits, return, duration, payout frequency, capacity, offer window) now live
 * on the asset itself, set by the admin per asset. The separate "investment
 * plans" concept is retired.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $add = function (string $name, callable $def) use ($table) {
                if (!Schema::hasColumn('assets', $name)) {
                    $def($table);
                }
            };

            $add('amount_type', fn ($t) => $t->enum('amount_type', ['fixed', 'ranged'])->default('ranged')->after('status'));
            $add('fixed_amount', fn ($t) => $t->decimal('fixed_amount', 20, 2)->nullable()->after('amount_type'));
            $add('min_amount', fn ($t) => $t->decimal('min_amount', 20, 2)->nullable()->after('fixed_amount'));
            $add('max_amount', fn ($t) => $t->decimal('max_amount', 20, 2)->nullable()->after('min_amount'));
            $add('return_type', fn ($t) => $t->enum('return_type', ['percentage', 'fixed'])->default('percentage')->after('max_amount'));
            $add('fixed_return', fn ($t) => $t->decimal('fixed_return', 20, 2)->nullable()->after('return_type'));
            $add('return_percentage', fn ($t) => $t->decimal('return_percentage', 8, 2)->nullable()->after('fixed_return'));
            $add('duration', fn ($t) => $t->integer('duration')->nullable()->after('return_percentage'));
            $add('duration_type', fn ($t) => $t->string('duration_type')->default('days')->after('duration'));
            $add('payout_interval', fn ($t) => $t->string('payout_interval')->default('monthly')->after('duration_type'));
            $add('capacity_amount', fn ($t) => $t->decimal('capacity_amount', 20, 2)->nullable()->after('payout_interval'));
            $add('offer_starts_at', fn ($t) => $t->datetime('offer_starts_at')->nullable()->after('capacity_amount'));
            $add('offer_ends_at', fn ($t) => $t->datetime('offer_ends_at')->nullable()->after('offer_starts_at'));
        });

        Schema::table('user_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('user_plans', 'asset_id')) {
                $table->unsignedBigInteger('asset_id')->nullable()->after('plan_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            foreach ([
                'amount_type', 'fixed_amount', 'min_amount', 'max_amount', 'return_type',
                'fixed_return', 'return_percentage', 'duration', 'duration_type',
                'payout_interval', 'capacity_amount', 'offer_starts_at', 'offer_ends_at',
            ] as $col) {
                if (Schema::hasColumn('assets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
