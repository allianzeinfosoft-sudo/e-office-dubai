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
        Schema::table('payroll_batches', function (Blueprint $row) {
             $row->unsignedBigInteger('salary_structure_id')->nullable()->after('department_id');
             $row->foreign('salary_structure_id')->references('id')->on('salary_structures')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_batches', function (Blueprint $table) {
             $table->dropForeign(['salary_structure_id']);
             $table->dropColumn('salary_structure_id');
        });
    }
};
