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
        Schema::table('asset_mappings', function (Blueprint $table) {
            //
            $table->string('model')->nullable()->after('register_lineitem_id');
            $table->string('serial_number')->nullable()->after('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_mappings', function (Blueprint $table) {
            //
            $table->dropColumn('model');
            $table->dropColumn('serial_number');
        });
    }
};
