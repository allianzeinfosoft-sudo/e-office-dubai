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
        Schema::create('asset_item_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_register_id');
            $table->integer('asset_item_id');
            $table->string('asset_brand')->nullable();
            $table->string('item_model', 50) ->nullable();
            $table->text('asset_description')->nullable();
            $table->integer('asset_classification_id');
            $table->integer('asset_category_id');
            $table->integer('asset_type_id');
            $table->float('asset_quantity', 50, 2);
            $table->float('asset_price', 50, 2);
            $table->float('asset_total', 50, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_item_lines');
    }
};
