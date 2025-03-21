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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('username', 100)->nullable()->change();
            $table->string('emp_id', 150)->nullable()->change();
            $table->date('signin_date')->nullable()->change();
            $table->string('signin_time', 50)->nullable()->change();
            $table->longText('signin_late_note')->nullable()->change();
            $table->string('signout_time', 50)->nullable()->change();
            $table->longText('signout_late_note')->nullable()->change();
            $table->longText('working_hours')->nullable()->change();
            $table->string('break_time', 200)->nullable()->change();
            $table->string('status', 11)->nullable()->change();
            $table->text('pre_experience')->nullable()->change();
            $table->string('punchin_type', 255)->nullable()->change();
            $table->string('punchout_type', 255)->nullable()->change();
            $table->string('custom_status', 255)->nullable()->change();
            $table->dateTime('signout_date')->nullable()->change();
            $table->string('actual_signout_date', 255)->nullable()->change();
            $table->integer('pending')->nullable()->change();
            $table->string('ipaddress', 200)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('username', 100)->nullable(false)->change();
            $table->string('emp_id', 150)->nullable(false)->change();
            $table->date('signin_date')->nullable(false)->change();
            $table->string('signin_time', 50)->nullable(false)->change();
            $table->longText('signin_late_note')->nullable(false)->change();
            $table->string('signout_time', 50)->nullable(false)->change();
            $table->longText('signout_late_note')->nullable(false)->change();
            $table->longText('working_hours')->nullable(false)->change();
            $table->string('break_time', 200)->nullable(false)->change();
            $table->string('status', 11)->nullable(false)->change();
            $table->text('pre_experience')->nullable(false)->change();
            $table->string('punchin_type', 255)->nullable(false)->change();
            $table->string('punchout_type', 255)->nullable(false)->change();
            $table->string('custom_status', 255)->nullable(false)->change();
            $table->dateTime('signout_date')->nullable(false)->change();
            $table->string('actual_signout_date', 255)->nullable(false)->change();
            $table->integer('pending')->nullable(false)->change();
            $table->string('ipaddress', 200)->nullable(false)->change();
        });
    }
};
