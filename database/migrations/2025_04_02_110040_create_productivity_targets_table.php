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
        Schema::create('productivity_targets', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('project_task_id');
            $table->string('target_month', 50);
            $table->string('target_year', 50);
            $table->string('rph', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productivity_targets');
    }
};
