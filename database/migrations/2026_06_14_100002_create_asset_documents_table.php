<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 2 — official documents attached to an Asset:
 *   - type "certificate": government certificate file(s)
 *   - type "supporting": supporting documents
 * Files themselves are stored on the private "local" disk; only the metadata
 * and relative path live here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->enum('type', ['certificate', 'supporting'])->default('certificate');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->index(['asset_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_documents');
    }
};
