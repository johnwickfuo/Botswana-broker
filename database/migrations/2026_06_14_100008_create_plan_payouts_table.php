<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 6 — the payout ledger/schedule for investments (the PlanPayout model
 * existed without a table). Each row is a scheduled payout:
 *   - type "return"    : a slice of the expected return, due on its date
 *   - type "principal" : the principal, due at maturity
 * status moves pending -> processed once credited to the wallet.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_plan_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->decimal('amount', 20, 2);
            $table->decimal('roi_percentage', 8, 2)->nullable();
            $table->string('type')->default('return');      // return | principal
            $table->string('status')->default('pending');   // pending | processed
            $table->datetime('due_date')->nullable()->index();
            $table->datetime('processed_at')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_payouts');
    }
};
