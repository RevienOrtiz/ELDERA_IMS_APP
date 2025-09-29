<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('event_type', ['general', 'pension', 'health', 'id_claiming']);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('location', 255);
            $table->string('organizer', 255)->nullable();
            $table->string('contact_person', 255)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->text('requirements')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index('event_date');
            $table->index('event_type');
            $table->index('status');
            $table->index(['event_date', 'status']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

























