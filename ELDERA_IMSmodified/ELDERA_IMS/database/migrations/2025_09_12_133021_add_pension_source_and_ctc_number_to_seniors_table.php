<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            // Add missing fields that are used in forms but not in database
            $table->string('pension_source', 255)->nullable()->after('has_pension');
            $table->string('ctc_number', 50)->nullable()->after('pension_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            $table->dropColumn(['pension_source', 'ctc_number']);
        });
    }
};