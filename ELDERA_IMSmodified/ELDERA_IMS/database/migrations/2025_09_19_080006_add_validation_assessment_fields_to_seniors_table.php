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
            $table->text('findings_concerns')->nullable()->after('certification');
            $table->enum('initial_assessment', ['eligible', 'ineligible'])->nullable()->after('findings_concerns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seniors', function (Blueprint $table) {
            $table->dropColumn(['findings_concerns', 'initial_assessment']);
        });
    }
};
