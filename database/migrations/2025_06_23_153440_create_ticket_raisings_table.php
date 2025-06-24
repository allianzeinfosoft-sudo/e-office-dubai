<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('ticket_raisings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('ticket_department');
            $table->string('ticket_title')->nullable();
            $table->text('ticket_description')->nullable();
            $table->dateTime('issue_date_time');
            $table->dateTime('close_date_time')->nullable();
            $table->string('picture')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_raisings');
    }
};
