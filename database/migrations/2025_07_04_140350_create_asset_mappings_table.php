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
        Schema::create('asset_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('master_item_id');
            $table->string('register_lineitem_id');
            $table->integer('item_number')->comment('asset id number');
            $table->integer('allocation_id')->nullable();
            $table->integer('scrap_id')->nullable();
            $table->integer('repair_id')->nullable();
            $table->integer('allocation_status')->default(0)->comment('0-store,1-allocated,2-repair,3-scrap');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_mappings');
    }
};
