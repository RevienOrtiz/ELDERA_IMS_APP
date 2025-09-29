<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('benefits_applications');
    }
};

























