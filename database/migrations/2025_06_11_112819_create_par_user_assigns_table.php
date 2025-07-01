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
        Schema::create('par_user_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('template_id');
            $table->string('par_name')->nullable();
            $table->string('par_code');
            $table->unsignedBigInteger('assigned_by');
            $table->date('par_start_date');
            $table->date('par_end_date');
            $table->date('par_submit_date')->nullable();
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
        Schema::dropIfExists('par_user_assigns');
    }
};
