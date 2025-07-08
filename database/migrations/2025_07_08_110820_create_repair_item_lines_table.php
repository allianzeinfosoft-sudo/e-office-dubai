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
        Schema::create('repair_item_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('repair_register_id');
            $table->integer('item_master_id');
            $table->string('item_model')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('asset_map_id')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2)->default(0.00);
            $table->decimal('rate', 10, 2)->default(0.00);
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->date('repair_date')->nullable();
            $table->decimal('return_amount', 10, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('repair_item_lines');
    }
};
