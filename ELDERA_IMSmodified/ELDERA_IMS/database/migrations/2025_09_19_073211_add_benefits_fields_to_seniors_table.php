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
            // Benefits form specific fields
            $table->string('civil_status', 50)->nullable()->after('marital_status'); // Civil Status for benefits form
            $table->string('citizenship', 50)->nullable()->after('civil_status'); // Citizenship (Filipino/Dual)
            $table->text('dual_citizenship_details')->nullable()->after('citizenship'); // Dual citizenship details
            $table->string('spouse_name', 255)->nullable()->after('spouse_extension'); // Full spouse name for benefits
            $table->string('spouse_citizenship', 100)->nullable()->after('spouse_name'); // Spouse citizenship
            $table->json('children')->nullable()->after('spouse_citizenship'); // Children names array
            $table->json('authorized_reps')->nullable()->after('children'); // Authorized representatives
            $table->string('primary_beneficiary', 255)->nullable()->after('authorized_reps'); // Primary beneficiary
            $table->string('contingent_beneficiary', 255)->nullable()->after('primary_beneficiary'); // Contingent beneficiary
            $table->json('utilization')->nullable()->after('contingent_beneficiary'); // Utilization of cash gifts
            $table->text('utilization_others')->nullable()->after('utilization'); // Other utilization details
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            $table->dropColumn([
                'civil_status',
                'citizenship', 
                'dual_citizenship_details',
                'spouse_name',
                'spouse_citizenship',
                'children',
                'authorized_reps',
                'primary_beneficiary',
                'contingent_beneficiary',
                'utilization',
                'utilization_others'
            ]);
        });
    }
};
