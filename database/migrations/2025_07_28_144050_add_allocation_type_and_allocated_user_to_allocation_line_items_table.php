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
        Schema::table('allocation_line_items', function (Blueprint $table) {
            $table->string('allocation_type')->nullable()->after('asset_mapping_id'); // replace 'existing_column' with the column after which it should appear
            $table->unsignedBigInteger('allocated_user')->nullable()->after('allocation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocation_line_items', function (Blueprint $table) {
             $table->dropColumn(['allocation_type', 'allocated_user']);
        });
    }
};
