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
        //
        Schema::create('user_entry_block_list', function (Blueprint $table) {
            $table->id();
            $table->date('block_date');
            $table->integer('user_id');  //User::id
            $table->string('username')->nullable();  //User::username
            $table->string('full_name', 255)->nullable();
            $table->integer('status')->default(1)->nullable()->comment('0 = Weightlist, 1 = blcoklist');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('user_entry_block_list');
    }
};
