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
            // II. FAMILY COMPOSITION
            $table->string('spouse_last_name', 255)->nullable();
            $table->string('spouse_first_name', 255)->nullable();
            $table->string('spouse_middle_name', 255)->nullable();
            $table->string('spouse_extension', 10)->nullable();
            $table->string('father_last_name', 255)->nullable();
            $table->string('father_first_name', 255)->nullable();
            $table->string('father_middle_name', 255)->nullable();
            $table->string('father_extension', 10)->nullable();
            $table->string('mother_last_name', 255)->nullable();
            $table->string('mother_first_name', 255)->nullable();
            $table->string('mother_middle_name', 255)->nullable();
            $table->string('mother_extension', 10)->nullable();
            
            // III. EDUCATION / HR PROFILE
            $table->string('education_level', 255)->nullable();
            $table->text('skills')->nullable(); // JSON field for skills array
            $table->text('shared_skills')->nullable();
            $table->text('community_activities')->nullable(); // JSON field for community activities array
            
            // IV. DEPENDENCY PROFILE
            $table->string('living_condition_primary', 255)->nullable();
            $table->text('living_with')->nullable(); // JSON field for living with array
            $table->text('household_condition')->nullable(); // JSON field for household condition array
            
            // V. ECONOMIC PROFILE
            $table->text('source_of_income')->nullable(); // JSON field for source of income array
            $table->text('real_assets')->nullable(); // JSON field for real assets array
            $table->text('personal_assets')->nullable(); // JSON field for personal assets array
            $table->string('monthly_income', 255)->nullable();
            $table->text('problems_needs')->nullable(); // JSON field for problems/needs array
            
            // VI. HEALTH PROFILE
            $table->string('blood_type', 10)->nullable();
            $table->string('physical_disability', 255)->nullable();
            $table->text('health_problems')->nullable(); // JSON field for health problems array
            $table->text('dental_concern')->nullable(); // JSON field for dental concern array
            $table->text('visual_concern')->nullable(); // JSON field for visual concern array
            $table->text('hearing_condition')->nullable(); // JSON field for hearing condition array
            $table->text('social_emotional')->nullable(); // JSON field for social/emotional array
            $table->text('area_difficulty')->nullable(); // JSON field for area/difficulty array
            $table->text('maintenance_medicines')->nullable();
            $table->string('scheduled_checkup', 255)->nullable();
            $table->string('checkup_frequency', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            // II. FAMILY COMPOSITION
            $table->dropColumn([
                'spouse_last_name', 'spouse_first_name', 'spouse_middle_name', 'spouse_extension',
                'father_last_name', 'father_first_name', 'father_middle_name', 'father_extension',
                'mother_last_name', 'mother_first_name', 'mother_middle_name', 'mother_extension'
            ]);
            
            // III. EDUCATION / HR PROFILE
            $table->dropColumn(['education_level', 'skills', 'shared_skills', 'community_activities']);
            
            // IV. DEPENDENCY PROFILE
            $table->dropColumn(['living_condition_primary', 'living_with', 'household_condition']);
            
            // V. ECONOMIC PROFILE
            $table->dropColumn(['source_of_income', 'real_assets', 'personal_assets', 'monthly_income', 'problems_needs']);
            
            // VI. HEALTH PROFILE
            $table->dropColumn([
                'blood_type', 'physical_disability', 'health_problems', 'dental_concern',
                'visual_concern', 'hearing_condition', 'social_emotional', 'area_difficulty',
                'maintenance_medicines', 'scheduled_checkup', 'checkup_frequency'
            ]);
        });
    }
};
