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
        Schema::table('appreciations', function (Blueprint $table) {
             $table->date('display_end_date')->nullable()->after('display_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appreciations', function (Blueprint $table) {
            $table->dropColumn('display_end_date');
        });
    }
};
