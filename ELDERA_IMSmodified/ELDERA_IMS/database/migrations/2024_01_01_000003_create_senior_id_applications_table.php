<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('senior_id_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('full_name', 255);
            $table->text('address');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth');
            $table->string('birth_place', 255);
            $table->string('occupation', 255)->nullable();
            $table->string('civil_status', 50);
            $table->decimal('annual_income', 15, 2)->nullable();
            $table->string('pension_source', 255)->nullable();
            $table->string('ctc_number', 50)->nullable();
            $table->string('place_of_issuance', 255)->nullable();
            $table->string('contact_number', 20);
            $table->timestamps();

            // Indexes
            $table->index('application_id');
            $table->index('full_name');
            $table->index('ctc_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('senior_id_applications');
    }
};

























