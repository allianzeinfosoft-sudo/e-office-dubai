<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salary_components', function (Blueprint $table) {
            $table->boolean('is_attendance_based')->default(false)->after('is_ctc_variable');
        });

        Schema::table('payroll_components', function (Blueprint $table) {
            $table->boolean('is_attendance_based')->default(false)->after('is_ctc_variable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_components', function (Blueprint $table) {
            $table->dropColumn('is_attendance_based');
        });

        Schema::table('payroll_components', function (Blueprint $table) {
            $table->dropColumn('is_attendance_based');
        });
    }
};
