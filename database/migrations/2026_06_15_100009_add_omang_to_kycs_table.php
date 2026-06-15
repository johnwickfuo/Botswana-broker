<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 7 — capture the Omang (Botswana national ID) number on KYC
 * applications so the admin review queue can verify identity.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kycs', function (Blueprint $table) {
            if (!Schema::hasColumn('kycs', 'id_number')) {
                $table->string('id_number')->nullable()->after('document_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kycs', function (Blueprint $table) {
            if (Schema::hasColumn('kycs', 'id_number')) {
                $table->dropColumn('id_number');
            }
        });
    }
};
