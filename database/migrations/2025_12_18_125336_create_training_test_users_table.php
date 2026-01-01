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
        Schema::create('training_test_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('acceptance_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('attempt_status', ['not_started', 'in_progress', 'submitted', 'expired'])->default('not_started');
            $table->enum('status', ['pending','completed'])->default('pending');
            $table->integer('score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_test_users');
    }
};
