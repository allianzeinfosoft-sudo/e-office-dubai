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
        Schema::table('work_from_home_attendances', function (Blueprint $table) {
            $table->date('signout_date')->nullable()->change();
            $table->string('signout_time', 50)->nullable()->change();
            $table->string('working_hours', 50)->nullable()->change();
            $table->string('break_time', 50)->nullable()->change();
            $table->string('is_incomplete', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_from_home_attendances', function (Blueprint $table) {
            $table->date('signout_date')->nullable(false)->change();
            $table->string('signout_time', 50)->nullable(false)->change();
            $table->string('working_hours', 50)->nullable(false)->change();
            $table->string('break_time', 50)->nullable(false)->change();
            $table->string('is_incomplete', 50)->nullable(false)->change();
        });
    }
};
