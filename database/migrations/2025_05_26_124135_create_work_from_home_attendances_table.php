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
        Schema::create('work_from_home_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('emp_id', 100);
            $table->date('signin_date');
            $table->string('signin_time',50);
            $table->date('signout_date');
            $table->string('signout_time',50);
            $table->string('working_hours',50);
            $table->string('break_time',50);
            $table->string('status',11);
            $table->string('ipaddress',200);
            $table->string('is_incomplete',50);
            $table->string('created_by',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_from_home_attendances');
    }
};
