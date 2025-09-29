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
        Schema::table('pension_applications', function (Blueprint $table) {
            $table->string('permanent_income', 10)->nullable();
            $table->string('income_amount', 50)->nullable();
            $table->string('income_source', 255)->nullable();
            $table->string('existing_illness', 10)->nullable(); // yes/no
            $table->string('illness_specify', 255)->nullable();
            $table->string('with_disability', 10)->nullable(); // yes/no
            $table->string('disability_specify', 255)->nullable();
            $table->json('living_arrangement')->nullable();
            $table->boolean('certification')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pension_applications', function (Blueprint $table) {
            $table->dropColumn([
                'permanent_income',
                'income_amount',
                'income_source',
                'existing_illness',
                'illness_specify',
                'with_disability',
                'disability_specify',
                'living_arrangement',
                'certification'
            ]);
        });
    }
};
