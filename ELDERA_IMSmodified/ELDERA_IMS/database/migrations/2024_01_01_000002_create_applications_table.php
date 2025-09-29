<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('senior_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('application_type', ['senior_id', 'pension', 'benefits']);
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->useCurrent();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->date('estimated_completion_date')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('senior_id');
            $table->index('application_type');
            $table->index('status');
            $table->index(['application_type', 'status']);
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

























