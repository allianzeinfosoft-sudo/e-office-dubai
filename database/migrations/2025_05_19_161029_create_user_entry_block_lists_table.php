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
        Schema::create('user_entry_block_lists', function (Blueprint $table) {
            $table->id();
            $table->date('block_date')->nullable();
            $table->integer('user_id');
            $table->string('username', 255);
            $table->string('full_name', 255)->nullable();
            $table->integer('status')->nullable()->default(1)->comment('0 = Weight List, 1 = Block List');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_entry_block_lists');
    }
};
