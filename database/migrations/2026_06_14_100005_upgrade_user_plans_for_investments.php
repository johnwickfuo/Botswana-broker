<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 5 — bring the legacy `user_plans` table up to the modern UserPlan
 * model (whose columns were never created) and add the investment-record
 * fields the invest flow needs. The UserPlan record IS the citizen's
 * Investment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $add = function (string $name, callable $definition) use ($table) {
                if (!Schema::hasColumn('user_plans', $name)) {
                    $definition($table);
                }
            };

            // Modern UserPlan columns (previously missing).
            $add('user_id', fn ($t) => $t->unsignedBigInteger('user_id')->nullable()->index());
            $add('plan_id', fn ($t) => $t->unsignedBigInteger('plan_id')->nullable()->index());
            $add('invested_amount', fn ($t) => $t->decimal('invested_amount', 20, 2)->nullable());
            $add('current_value', fn ($t) => $t->decimal('current_value', 20, 2)->nullable());
            $add('roi_percentage', fn ($t) => $t->decimal('roi_percentage', 8, 2)->nullable());
            $add('expected_return', fn ($t) => $t->decimal('expected_return', 20, 2)->nullable());
            $add('total_profit', fn ($t) => $t->decimal('total_profit', 20, 2)->default(0));
            $add('status', fn ($t) => $t->string('status')->default('active')->index());
            $add('activated_at', fn ($t) => $t->datetime('activated_at')->nullable());
            $add('expires_at', fn ($t) => $t->datetime('expires_at')->nullable());
            $add('last_payout_at', fn ($t) => $t->datetime('last_payout_at')->nullable());
            $add('compounding_enabled', fn ($t) => $t->boolean('compounding_enabled')->default(false));
            $add('compounding_percentage', fn ($t) => $t->decimal('compounding_percentage', 8, 2)->nullable());
            $add('payment_method', fn ($t) => $t->string('payment_method')->nullable());
            $add('payment_reference', fn ($t) => $t->string('payment_reference')->nullable());
            $add('notes', fn ($t) => $t->text('notes')->nullable());

            // Phase 5 investment-record fields.
            $add('start_date', fn ($t) => $t->datetime('start_date')->nullable());
            $add('maturity_date', fn ($t) => $t->datetime('maturity_date')->nullable());
            $add('locked', fn ($t) => $t->boolean('locked')->default(false));
            $add('terms_accepted_at', fn ($t) => $t->datetime('terms_accepted_at')->nullable());

            $add('deleted_at', fn ($t) => $t->softDeletes());
        });
    }

    public function down(): void
    {
        // Non-destructive: leave the columns in place. The legacy table had no
        // modern columns to restore, and dropping them risks data loss.
    }
};
