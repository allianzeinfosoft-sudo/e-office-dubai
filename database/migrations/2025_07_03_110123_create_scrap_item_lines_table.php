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
        Schema::create('scrap_item_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('scrap_register_id');
            $table->integer('scrap_item_id');
            $table->string('serial_no',50)->nullable();
            $table->string('unit',20)->nullable();
            $table->decimal('quantity', 10, 3)->nullable();
            $table->decimal('rate', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrap_item_lines');
    }
};
