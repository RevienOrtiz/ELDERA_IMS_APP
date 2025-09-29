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
            // Add missing fields that the pension form expects
            $table->string('permanent_income', 10)->nullable(); // Yes/No
            $table->string('income_amount', 255)->nullable(); // Amount of permanent income
            $table->string('income_source', 255)->nullable(); // Source of permanent income
            $table->string('existing_illness', 10)->nullable(); // yes/no
            $table->text('illness_specify')->nullable(); // Specify illness details
            $table->string('with_disability', 10)->nullable(); // yes/no
            $table->text('disability_specify')->nullable(); // Specify disability details
            $table->boolean('certification')->nullable(); // Certification checkbox
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            $table->dropColumn([
                'permanent_income',
                'income_amount', 
                'income_source',
                'existing_illness',
                'illness_specify',
                'with_disability',
                'disability_specify',
                'certification'
            ]);
        });
    }
};
