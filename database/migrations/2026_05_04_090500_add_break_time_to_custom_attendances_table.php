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
        Schema::table('custom_attendances', function (Blueprint $table) {
            $table->string('break_time', 200)->nullable()->after('signin_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_attendances', function (Blueprint $table) {
            $table->dropColumn('break_time');
        });
    }
};
