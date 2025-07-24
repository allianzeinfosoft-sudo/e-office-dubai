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
        Schema::create('asset_registers', function (Blueprint $table) {
            $table->id();
            $table->date('asset_date');
            $table->string('asset_number', 15)->nullable();
            $table->string('company_name', 15);
            $table->date('purchase_date');
            $table->integer('vendor_id');
            $table->string('invoice_number', 15)->nullable();
            $table->float('total_amount', 50, 2) ->nullable();
            $table->string('upload_invoice', 100) ->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_registers');
    }
};
