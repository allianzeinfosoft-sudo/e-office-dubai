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
        Schema::create('mail_boxes', function (Blueprint $table) {
            $table->id();
            $table->json('to_user_ids')->nullable();
            $table->json('cc_user_ids')->nullable();
            $table->json('bcc_user_ids')->nullable();
            $table->string('subject', 255);
            $table->longText('message');
            $table->json('attachments')->nullable();  // For multiple files
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_boxes');
    }
};
