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
        Schema::create('feedback_assigns', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('feedback_id');
            $table->unsignedBigInteger('assigned_by');
            $table->date('feedback_start_date');
            $table->date('feedback_end_date');
            $table->date('feedback_submit_date')->nullable();
            $table->integer('status')->default(1)->comment('1-pending,2-completed');
            $table->integer('total_score')->nullable();
            $table->integer('maximum_score')->nullable();
            $table->decimal('score_percentage', 10, 2)->nullable();
            $table->string('grade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_assigns');
    }
};
