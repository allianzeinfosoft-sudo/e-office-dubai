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
        Schema::create('work_from_home_reports', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->nullable();
            $table->integer('emp_id')->nullable();
            $table->string('project_name', 50)->nullable();
            $table->string('type_of_work', 50)->nullable();
            $table->string('time_of_work', 50)->nullable();
            $table->string('total_time', 50)->nullable();
            $table->longText('comments')->nullable();
            $table->date('report_date')->nullable();
            $table->string('total_records', 100)->nullable();
            $table->integer('productivity_hour')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_from_home_reports');
    }
};
