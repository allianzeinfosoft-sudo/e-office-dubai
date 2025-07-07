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
        Schema::table('scrap_item_lines', function (Blueprint $table) {
            //
            $table->integer('asset_mapping_id')->nullable()->after('serial_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scrap_item_lines', function (Blueprint $table) {
            //
            $table->dropColumn('asset_mapping_id');
        });
    }
};
