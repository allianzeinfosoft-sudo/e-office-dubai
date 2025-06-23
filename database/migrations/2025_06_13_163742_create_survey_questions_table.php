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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->text('question');
            $table->enum('answer_type', ['yes_no', 'optional', 'description']);
            $table->json('options')->nullable();     // For optional type
            $table->timestamps();
             $table->foreign('template_id')
                  ->references('id')->on('survey_templates')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
