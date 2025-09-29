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
        Schema::table('benefits_applications', function (Blueprint $table) {
            $table->dropColumn(['benefit_type', 'reason']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('benefits_applications', function (Blueprint $table) {
            $table->enum('benefit_type', ['medical', 'burial', 'financial', 'others'])->after('application_id');
            $table->text('reason')->after('benefit_type');
        });
    }
};
