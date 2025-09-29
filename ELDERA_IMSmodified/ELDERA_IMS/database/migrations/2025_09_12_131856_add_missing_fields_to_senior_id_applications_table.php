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
        Schema::table('senior_id_applications', function (Blueprint $table) {
            // Add missing fields that are used in the form
            $table->date('date_of_application')->nullable()->after('contact_number');
            $table->date('date_of_issued')->nullable()->after('date_of_application');
            $table->date('date_of_received')->nullable()->after('date_of_issued');
            
            // Make annual_income required (remove nullable)
            $table->decimal('annual_income', 15, 2)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senior_id_applications', function (Blueprint $table) {
            // Remove the added fields
            $table->dropColumn(['date_of_application', 'date_of_issued', 'date_of_received']);
            
            // Revert annual_income to nullable
            $table->decimal('annual_income', 15, 2)->nullable()->change();
        });
    }
};