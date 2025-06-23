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
        Schema::create('sar_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->text('question');
            // For optional type
            $table->timestamps();
             $table->foreign('template_id')
                  ->references('id')->on('sar_templates')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sar_questions');
    }
};
