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
        Schema::create('workshift_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workshift_id');
            $table->string('day');
            $table->time('shift_start_time')->nullable();
            $table->time('shift_end_time')->nullable();
            $table->time('mini_break_time')->nullable();
            $table->time('max_break_time')->nullable();
            $table->timestamps();

            $table->foreign('workshift_id')->references('id')->on('workshifts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshift_details');
    }
};
