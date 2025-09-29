<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seniors', function (Blueprint $table) {
            $table->id();
            $table->string('osca_id', 50)->unique();
            $table->string('last_name', 255);
            $table->string('first_name', 255);
            $table->string('middle_name', 255);
            $table->string('name_extension', 10)->nullable();
            $table->string('region', 255);
            $table->string('province', 255);
            $table->string('city', 255);
            $table->string('barangay', 255);
            $table->string('residence', 255);
            $table->string('street', 255)->nullable();
            $table->date('date_of_birth');
            $table->string('birth_place', 255);
            $table->enum('marital_status', ['Single', 'Married', 'Widowed', 'Separated', 'Others']);
            $table->enum('sex', ['Male', 'Female']);
            $table->string('contact_number', 20);
            $table->string('email', 255)->nullable();
            $table->string('religion', 255)->nullable();
            $table->string('ethnic_origin', 255)->nullable();
            $table->string('language', 255);
            $table->string('gsis_sss', 50)->nullable();
            $table->string('tin', 50)->nullable();
            $table->string('philhealth', 50)->nullable();
            $table->string('sc_association', 50)->nullable();
            $table->string('other_govt_id', 50)->nullable();
            $table->boolean('can_travel')->nullable();
            $table->string('employment', 255)->nullable();
            $table->boolean('has_pension')->nullable();
            $table->enum('status', ['active', 'deceased'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('osca_id');
            $table->index('barangay');
            $table->index('status');
            $table->index('date_of_birth');
            $table->index(['barangay', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seniors');
    }
};



