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
        Schema::table('asset_item_lines', function (Blueprint $table) {
            $table->float('asset_quantity', 50, 2)->default(0.00)->nullable()->change();
            $table->float('asset_price', 50, 2)->default(0.00)->nullable()->change();
            $table->float('asset_total', 50, 2)->default(0.00)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_item_lines', function (Blueprint $table) {
           $table->float('asset_quantity', 50, 2)->default(null)->nullable(false)->change();
           $table->float('asset_price', 50, 2)->default(null)->nullable(false)->change();
           $table->float('asset_total', 50, 2)->default(null)->nullable(false)->change();
        });
    }
};
