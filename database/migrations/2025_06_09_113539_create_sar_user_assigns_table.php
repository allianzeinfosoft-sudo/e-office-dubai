<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sar_user_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('template_id');
            $table->string('sar_name')->nullable();
            $table->string('sar_code');
            $table->unsignedBigInteger('assigned_by');
            $table->date('sar_start_date');
            $table->date('sar_end_date');
            $table->date('sar_submit_date')->nullable();
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
        Schema::dropIfExists('sar_user_assigns');
    }
};
