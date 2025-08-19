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
        Schema::create('asset_expiries', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->nullable();
            $table->integer('asset_categories_id')->nullable();
            $table->integer('asset_vendors_id')->nullable();
            $table->string('licence_id')->nullable();
            $table->integer('licence_count')->nullable();
            $table->date('start_date')->nullable();
            $table->date('last_updated_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('cost')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_expiries');
    }
};
