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
        Schema::create('event_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('label')->nullable(); // Business, Personal, etc.
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('url')->nullable();
            $table->json('guests')->nullable(); // Store as array
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_calendars');
    }
};
