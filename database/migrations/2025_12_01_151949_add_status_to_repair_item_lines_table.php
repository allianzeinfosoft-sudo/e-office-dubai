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
        Schema::table('repair_item_lines', function (Blueprint $table) {
               $table->integer('status')
                ->comment('1 = sent, 2 = received')
                ->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_item_lines', function (Blueprint $table) {
           $table->dropColumn('status');
        });
    }
};
