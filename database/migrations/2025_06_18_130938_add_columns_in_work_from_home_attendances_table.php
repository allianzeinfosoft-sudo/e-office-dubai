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
        Schema::table('work_from_home_attendances', function (Blueprint $table) {
            //
            $table->integer('approvel_status')->default(0)->comment('0 = pending, 1 = approved, 2 = rejected')->after('is_incomplete');
            $table->integer('approved_by')->nullable()->after('approvel_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_from_home_attendances', function (Blueprint $table) {
            //
        });
    }
};
