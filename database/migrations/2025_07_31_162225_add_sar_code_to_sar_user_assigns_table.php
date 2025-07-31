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
        Schema::table('sar_user_assigns', function (Blueprint $table) {
             $table->string('sar_code')->nullable()->after('sar_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sar_user_assigns', function (Blueprint $table) {
           $table->dropColumn('sar_code');
        });
    }
};
