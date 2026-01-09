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
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('mail_protocol', 50)->nullable();
            $table->string('incoming_host', 255)->nullable();
            $table->integer('incoming_port')->nullable();
            $table->string('incoming_encryption', 255)->nullable();
            $table->string('incoming_username', 255)->nullable();
            $table->string('incoming_password', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_configurations');
    }
};
