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
        Schema::create('custom_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('username', 255);
            $table->integer('emp_id');
            $table->string('picktime', 50);
            $table->longText('reason');
            $table->date('signin_date');
            $table->integer('status');
            $table->integer('approved_by')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_attendances');
    }
};
