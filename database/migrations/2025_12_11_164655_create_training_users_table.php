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
        Schema::create('training_users', function (Blueprint $table) {
            $table->id();
            $table->integer('training_id');
            $table->integer('user_id');
            $table->string('acceptance_status')->default('pending');
            $table->string('attendance_status')->default('absent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_users');
    }
};
