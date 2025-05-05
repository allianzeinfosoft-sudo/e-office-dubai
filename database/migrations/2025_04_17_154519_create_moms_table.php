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
        Schema::create('moms', function (Blueprint $table) {
            $table->id();
            $table->string('mom_title', 255);
            $table->date('mom_date');
            $table->integer('created_by');
            $table->string('assigned_to')->nullable();
            $table->string('mom_details')->nullable();
            $table->string('attachments')->nullable();
            $table->integer('status')->default(0)->comments('0 = No, 1 = Yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moms');
    }
};
