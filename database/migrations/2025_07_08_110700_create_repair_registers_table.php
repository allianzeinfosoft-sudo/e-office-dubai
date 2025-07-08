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
        Schema::create('repair_registers', function (Blueprint $table) {
            $table->id();
            $table->string('repair_no');
            $table->date('repair_date');
            $table->integer('vendor_id');   
            $table->enum('status', ['sent', 'returned'])->default('sent');
            $table->date('return_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_registers');
    }
};
