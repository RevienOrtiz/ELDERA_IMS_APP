<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('senior_id')->constrained()->onDelete('cascade');
            $table->timestamp('registered_at')->useCurrent();
            $table->boolean('attended')->default(false);
            $table->text('attendance_notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique(['event_id', 'senior_id']);
            $table->index('event_id');
            $table->index('senior_id');
            $table->index('attended');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};

























