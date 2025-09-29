<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('rrn', 50)->nullable();
            $table->decimal('monthly_income', 15, 2);
            $table->boolean('has_pension')->default(false);
            $table->string('pension_source', 255)->nullable();
            $table->decimal('pension_amount', 15, 2)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('application_id');
            $table->index('rrn');
            $table->index('has_pension');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_applications');
    }
};

























