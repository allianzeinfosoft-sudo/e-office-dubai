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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('event_name');
            $table->time('display_time');
            $table->string('event_description');
            $table->string('repeat_status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('repeat_mode')->nullable();
            $table->string('monthly_type')->nullable();
            $table->string('day')->nullable();
            $table->string('on_day_status')->nullable();
            $table->string('monthly_on_week_position')->nullable();
            $table->string('yearly_in_month')->nullable();
            $table->string('active_satus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
