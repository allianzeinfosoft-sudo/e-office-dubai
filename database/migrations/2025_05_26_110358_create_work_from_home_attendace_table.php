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
        Schema::create('work_from_home_attendace', function (Blueprint $table) {
            $table->id();
            $table->string('username',100);
            $table->string('emp_id');
            $table->date('signin_date');
            $table->string('signin_time');
            $table->longText('signin_late_note');
            $table->string('signout_time');
            $table->longText('signout_late_note');
            $table->longText('working_hours');
            $table->string('break_time');
            $table->string('status');
            $table->text('pre_experience');
            $table->string('punchin_type');
            $table->string('punchout_type');
            $table->string('custom_status');
            $table->dateTime('signout_date');
            $table->string('actual_signout_date');
            $table->integer('pending');
            $table->string('ipaddress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_from_home_attendace');
    }
};
