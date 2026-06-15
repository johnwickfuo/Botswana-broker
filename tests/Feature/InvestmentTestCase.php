<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Phase 9 — base test case for the investment domain. Boots the app, then
 * points the default connection at a throwaway SQLite file and builds only the
 * tables the investment flow touches (the legacy migration set is not
 * SQLite-clean, so we provision the schema directly).
 */
abstract class InvestmentTestCase extends TestCase
{
    protected string $dbPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dbPath = tempnam(sys_get_temp_dir(), 'bwtest') . '.sqlite';
        file_put_contents($this->dbPath, '');

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => $this->dbPath]);
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->buildSchema();
        $this->seedSettings();
    }

    protected function tearDown(): void
    {
        @unlink($this->dbPath);
        parent::tearDown();
    }

    protected function buildSchema(): void
    {
        Schema::create('settings', function ($t) {
            $t->increments('id');
            $t->string('enable_kyc_registration')->nullable();
            $t->string('currency')->nullable();
            $t->text('modules')->nullable();
        });

        Schema::create('users', function ($t) {
            $t->increments('id');
            $t->string('name')->nullable();
            $t->string('l_name')->nullable();
            $t->string('email')->nullable();
            $t->string('account_bal')->nullable();
            $t->string('account_verify')->nullable();
            $t->string('currency')->nullable();
            $t->timestamps();
        });

        Schema::create('assets', function ($t) {
            $t->increments('id');
            $t->string('name')->nullable();
            $t->string('category')->nullable();
            $t->text('description')->nullable();
            $t->string('status')->default('active');
            $t->string('symbol')->nullable();
            $t->timestamps();
        });

        Schema::create('plans', function ($t) {
            $t->increments('id');
            $t->string('name')->nullable();
            $t->string('slug')->nullable();
            $t->text('description')->nullable();
            $t->unsignedBigInteger('asset_id')->nullable();
            $t->string('amount_type')->nullable();
            $t->decimal('fixed_amount', 20, 2)->nullable();
            $t->decimal('min_amount', 20, 2)->nullable();
            $t->decimal('max_amount', 20, 2)->nullable();
            $t->string('return_type')->nullable();
            $t->decimal('fixed_return', 20, 2)->nullable();
            $t->decimal('return_percentage', 8, 2)->nullable();
            $t->decimal('capacity_amount', 20, 2)->nullable();
            $t->dateTime('offer_starts_at')->nullable();
            $t->dateTime('offer_ends_at')->nullable();
            $t->integer('duration')->nullable();
            $t->string('duration_type')->nullable();
            $t->string('payout_interval')->nullable();
            $t->boolean('active')->default(true);
            $t->integer('sort_order')->nullable();
            $t->softDeletes();
            $t->timestamps();
        });

        Schema::create('user_plans', function ($t) {
            $t->increments('id');
            $t->integer('plan')->nullable();
            $t->integer('user')->nullable();
            $t->unsignedBigInteger('plan_id')->nullable();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->decimal('invested_amount', 20, 2)->nullable();
            $t->decimal('current_value', 20, 2)->nullable();
            $t->decimal('roi_percentage', 8, 2)->nullable();
            $t->decimal('expected_return', 20, 2)->nullable();
            $t->decimal('total_profit', 20, 2)->default(0);
            $t->string('status')->default('active');
            $t->dateTime('activated_at')->nullable();
            $t->dateTime('expires_at')->nullable();
            $t->dateTime('last_payout_at')->nullable();
            $t->boolean('compounding_enabled')->default(false);
            $t->dateTime('start_date')->nullable();
            $t->dateTime('maturity_date')->nullable();
            $t->boolean('locked')->default(false);
            $t->dateTime('terms_accepted_at')->nullable();
            $t->string('payment_method')->nullable();
            $t->softDeletes();
            $t->timestamps();
        });

        Schema::create('wallet_transactions', function ($t) {
            $t->increments('id');
            $t->unsignedBigInteger('user_id');
            $t->string('type');
            $t->decimal('amount', 20, 2);
            $t->decimal('balance_after', 20, 2);
            $t->string('description')->nullable();
            $t->string('reference_type')->nullable();
            $t->unsignedBigInteger('reference_id')->nullable();
            $t->timestamps();
        });

        Schema::create('plan_payouts', function ($t) {
            $t->increments('id');
            $t->unsignedBigInteger('user_plan_id');
            $t->unsignedBigInteger('user_id');
            $t->decimal('amount', 20, 2);
            $t->decimal('roi_percentage', 8, 2)->nullable();
            $t->string('type')->default('return');
            $t->string('status')->default('pending');
            $t->dateTime('due_date')->nullable();
            $t->dateTime('processed_at')->nullable();
            $t->string('remarks')->nullable();
            $t->timestamps();
        });

        Schema::create('audit_logs', function ($t) {
            $t->increments('id');
            $t->string('actor_type')->nullable();
            $t->unsignedBigInteger('actor_id')->nullable();
            $t->string('actor_name')->nullable();
            $t->string('action');
            $t->string('subject_type')->nullable();
            $t->unsignedBigInteger('subject_id')->nullable();
            $t->text('description')->nullable();
            $t->text('properties')->nullable();
            $t->string('ip_address')->nullable();
            $t->timestamps();
        });
    }

    protected function seedSettings(string $kyc = 'yes'): void
    {
        DB::table('settings')->insert(['id' => 1, 'enable_kyc_registration' => $kyc, 'currency' => 'P']);
    }

    protected function makeUser(float $balance, string $verify = 'Verified'): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => 'Citizen', 'email' => 'c' . uniqid() . '@bw.gov',
            'account_bal' => $balance, 'account_verify' => $verify, 'currency' => 'P',
        ]);

        return User::find($id);
    }

    protected function makePlan(array $attributes = []): Plan
    {
        return Plan::create(array_merge([
            'name' => 'Test Plan', 'active' => true,
            'amount_type' => 'ranged', 'min_amount' => 500, 'max_amount' => 5000,
            'return_type' => 'percentage', 'return_percentage' => 10,
            'duration' => 30, 'duration_type' => 'days', 'payout_interval' => 'monthly',
        ], $attributes));
    }
}
