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
        Schema::create('rrf_approval_levels', function (Blueprint $table) {
            $table->id();
            $table->string('department')->nullable();
            $table->string('approver')->nullable();
            $table->string('approval_level')->nullable();
            $table->integer('approve_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrf_approval_levels');
    }
};
