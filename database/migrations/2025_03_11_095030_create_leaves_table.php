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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->date('leave_from');
            $table->date('leave_to');
            $table->integer('user_id');
            $table->string('leave_type');
            $table->text('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
