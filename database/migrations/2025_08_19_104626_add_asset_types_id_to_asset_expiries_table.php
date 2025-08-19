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
        Schema::table('asset_expiries', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_types_id')->nullable()->after('asset_categories_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_expiries', function (Blueprint $table) {
             $table->dropColumn('asset_types_id');
        });
    }
};
