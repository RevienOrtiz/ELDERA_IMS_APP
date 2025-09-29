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
        // Drop the existing table and recreate it with the correct structure
        Schema::dropIfExists('benefits_applications');
        
        Schema::create('benefits_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('senior_id')->constrained()->onDelete('cascade');
            $table->integer('milestone_age');
            
            // Personal Information
            $table->string('rrn')->nullable();
            $table->string('osca_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();
            $table->date('date_of_birth');
            $table->integer('age');
            
            // Address Information
            $table->string('res_house_number')->nullable();
            $table->string('res_street')->nullable();
            $table->string('res_barangay')->nullable();
            $table->string('res_city')->nullable();
            $table->string('res_province')->nullable();
            $table->string('res_zip')->nullable();
            $table->string('perm_house_number')->nullable();
            $table->string('perm_street')->nullable();
            $table->string('perm_barangay')->nullable();
            $table->string('perm_city')->nullable();
            $table->string('perm_province')->nullable();
            $table->string('perm_zip')->nullable();
            
            // Personal Details
            $table->enum('sex', ['Male', 'Female']);
            $table->string('civil_status');
            $table->string('civil_status_others')->nullable();
            $table->enum('citizenship', ['Filipino', 'Dual']);
            $table->string('dual_citizenship_details')->nullable();
            
            // Family Information
            $table->text('spouse_name')->nullable();
            $table->string('spouse_citizenship')->nullable();
            $table->json('children')->nullable();
            $table->json('authorized_reps')->nullable();
            
            // Contact Information
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            
            // Beneficiaries
            $table->string('primary_beneficiary')->nullable();
            $table->string('contingent_beneficiary')->nullable();
            
            // Utilization
            $table->json('utilization')->nullable();
            $table->string('utilization_others')->nullable();
            
            // Certification
            $table->boolean('certification')->default(false);
            
            // Validation Assessment
            $table->text('findings_concerns')->nullable();
            $table->enum('initial_assessment', ['eligible', 'ineligible'])->nullable();
            
            // Documentary Requirements
            $table->string('applicant_type')->default('local');
            $table->string('local_annex_a')->nullable();
            $table->text('local_annex_a_remarks')->nullable();
            $table->string('local_primary_docs')->nullable();
            $table->text('local_primary_docs_remarks')->nullable();
            $table->string('local_id_picture')->nullable();
            $table->text('local_id_picture_remarks')->nullable();
            $table->string('local_full_body')->nullable();
            $table->text('local_full_body_remarks')->nullable();
            $table->string('local_endorsed_list')->nullable();
            $table->text('local_endorsed_list_remarks')->nullable();
            $table->string('abroad_annex_a')->nullable();
            $table->text('abroad_annex_a_remarks')->nullable();
            $table->string('abroad_primary_docs')->nullable();
            $table->text('abroad_primary_docs_remarks')->nullable();
            $table->string('abroad_id_picture')->nullable();
            $table->text('abroad_id_picture_remarks')->nullable();
            $table->string('abroad_full_body')->nullable();
            $table->text('abroad_full_body_remarks')->nullable();
            $table->string('abroad_endorsed_list')->nullable();
            $table->text('abroad_endorsed_list_remarks')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new table and recreate the original structure
        Schema::dropIfExists('benefits_applications');
        
        Schema::create('benefits_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->enum('benefit_type', ['medical', 'burial', 'financial', 'others']);
            $table->text('reason');
            $table->integer('milestone_age')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('application_id');
            $table->index('benefit_type');
            $table->index('milestone_age');
        });
    }
};
