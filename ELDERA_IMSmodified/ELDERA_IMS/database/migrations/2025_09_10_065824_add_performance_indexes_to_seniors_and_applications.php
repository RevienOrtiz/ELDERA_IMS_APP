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
        // Add additional indexes to seniors table for better performance
        Schema::table('seniors', function (Blueprint $table) {
            // Add composite indexes for common query patterns
            if (!Schema::hasIndex('seniors', 'seniors_barangay_status_index')) {
                $table->index(['barangay', 'status'], 'seniors_barangay_status_index');
            }
            if (!Schema::hasIndex('seniors', 'seniors_osca_id_index')) {
                $table->index('osca_id', 'seniors_osca_id_index');
            }
            if (!Schema::hasIndex('seniors', 'seniors_name_search_index')) {
                $table->index(['first_name', 'last_name'], 'seniors_name_search_index');
            }
            if (!Schema::hasIndex('seniors', 'seniors_created_at_index')) {
                $table->index('created_at', 'seniors_created_at_index');
            }
        });

        // Add additional indexes to applications table
        Schema::table('applications', function (Blueprint $table) {
            // Add indexes for senior relationship and pagination
            if (!Schema::hasIndex('applications', 'applications_senior_id_type_index')) {
                $table->index(['senior_id', 'application_type'], 'applications_senior_id_type_index');
            }
            if (!Schema::hasIndex('applications', 'applications_submitted_at_index')) {
                $table->index('submitted_at', 'applications_submitted_at_index');
            }
            if (!Schema::hasIndex('applications', 'applications_created_at_index')) {
                $table->index('created_at', 'applications_created_at_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the additional indexes
        Schema::table('seniors', function (Blueprint $table) {
            $table->dropIndex('seniors_barangay_status_index');
            $table->dropIndex('seniors_osca_id_index');
            $table->dropIndex('seniors_name_search_index');
            $table->dropIndex('seniors_created_at_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_senior_id_type_index');
            $table->dropIndex('applications_submitted_at_index');
            $table->dropIndex('applications_created_at_index');
        });
    }
};
