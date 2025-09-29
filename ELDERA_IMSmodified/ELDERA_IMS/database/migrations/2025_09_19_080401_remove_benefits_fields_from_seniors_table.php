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
            // Remove benefits-specific fields that should be in benefits_applications table
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
                'utilization_others',
                'findings_concerns',
                'initial_assessment'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            // Re-add the fields if migration is rolled back
            $table->string('civil_status')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('dual_citizenship_details')->nullable();
            $table->text('spouse_name')->nullable();
            $table->string('spouse_citizenship')->nullable();
            $table->json('children')->nullable();
            $table->json('authorized_reps')->nullable();
            $table->string('primary_beneficiary')->nullable();
            $table->string('contingent_beneficiary')->nullable();
            $table->json('utilization')->nullable();
            $table->string('utilization_others')->nullable();
            $table->text('findings_concerns')->nullable();
            $table->enum('initial_assessment', ['eligible', 'ineligible'])->nullable();
        });
    }
};
