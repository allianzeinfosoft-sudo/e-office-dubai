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
        Schema::create('conference_halls', function (Blueprint $table) {
            $table->id();
            $table->integer('department_id');
            $table->integer('booked_by');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('participants');
            $table->string('purpose')->nullable();
            $table->string('status')->default(0) ->comment('0 = Pending, 1 = confirmed, 2 = cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_halls');
    }
};
