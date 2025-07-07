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
        Schema::create('allocation_line_items', function (Blueprint $table) {
            $table->id();
            $table->integer('allocation_id');
            $table->string('item');
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_id')->nullable();
            $table->integer('project')->nullable();
            $table->integer('qty')->nullable();
            $table->text('specification')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('allocation_line_items');
    }
};
