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
            $table->string('model')->nullable()->after('scrap_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scrap_item_lines', function (Blueprint $table) {
            //
            $table->dropColumn('model');
        });
    }
};
