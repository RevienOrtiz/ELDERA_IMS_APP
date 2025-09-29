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
        Schema::table('pension_applications', function (Blueprint $table) {
            // Add senior_id column if it doesn't exist
            if (!Schema::hasColumn('pension_applications', 'senior_id')) {
                $table->foreignId('senior_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pension_applications', function (Blueprint $table) {
            if (Schema::hasColumn('pension_applications', 'senior_id')) {
                $table->dropForeign(['senior_id']);
                $table->dropColumn('senior_id');
            }
        });
    }
};