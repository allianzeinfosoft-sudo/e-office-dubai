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
        Schema::create('helper_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notification_type')->nullable();
            $table->json('recipients_ids')->nullable();
            $table->text('message')->nullable();
            $table->json('readers_ids')->nullable(); // Users who read it
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
