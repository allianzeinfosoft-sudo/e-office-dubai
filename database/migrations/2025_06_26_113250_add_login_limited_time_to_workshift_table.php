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
        Schema::table('workshifts', function (Blueprint $table) {
            $table->time('login_limited_time')->nullable()->after('shift_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshift', function (Blueprint $table) {
           $table->dropColumn('login_limited_time');
        });
    }
};
