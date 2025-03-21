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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('emp_id', 150);
            $table->date('signin_date');
            $table->string('signin_time', 50);
            $table->longText('signin_late_note');
            $table->string('signout_time', 50);
            $table->longText('signout_late_note');
            $table->longText('working_hours');
            $table->string('break_time', 200);
            $table->string('status', 11);
            $table->text('pre_experience');
            $table->string('punchin_type', 255);
            $table->string('punchout_type', 255);
            $table->string('custom_status', 255);
            $table->dateTime('signout_date');
            $table->string('actual_signout_date', 255); 
            $table->integer('pending');
            $table->string('ipaddress', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
