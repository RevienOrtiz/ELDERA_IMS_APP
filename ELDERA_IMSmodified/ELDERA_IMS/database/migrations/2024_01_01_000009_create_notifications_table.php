<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('senior_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->text('message');
            $table->enum('type', ['application_update', 'event_reminder', 'system_alert', 'pension_reminder']);
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('senior_id');
            $table->index('is_read');
            $table->index('type');
            $table->index(['user_id', 'is_read']);
            $table->index(['senior_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

























