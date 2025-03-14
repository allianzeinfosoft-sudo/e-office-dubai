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
        Schema::create('workshifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_id');
            $table->time('shift_start_time');
            $table->time('shift_end_time');
            $table->time('mini_break_time')->nullable();
            $table->time('max_break_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshifts');
    }
};
