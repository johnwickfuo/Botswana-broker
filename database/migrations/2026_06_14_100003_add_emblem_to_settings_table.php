<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 2 — configurable national emblem / coat-of-arms slot. The emblem is
 * ALWAYS client-uploaded (never fabricated); this column holds the stored
 * path. A neutral placeholder is shown until the client uploads one.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'emblem')) {
                $table->string('emblem')->nullable()->after('logo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'emblem')) {
                $table->dropColumn('emblem');
            }
        });
    }
};
