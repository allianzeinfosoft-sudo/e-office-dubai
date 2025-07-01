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
        Schema::create('survey_user_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('template_id');
            $table->string('survey_name')->nullable();
            $table->unsignedBigInteger('assigned_by');
            $table->date('survey_start_date');
            $table->date('survey_end_date');
            $table->date('survey_submit_date')->nullable();
            $table->integer('status')->default(1)->comment('1-pending,2-completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_user_assigns');
    }
};
