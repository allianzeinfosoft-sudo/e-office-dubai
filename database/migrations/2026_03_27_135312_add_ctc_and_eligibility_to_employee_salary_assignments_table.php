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
        Schema::table('employee_salary_assignments', function (Blueprint $table) {
            $table->decimal('monthly_ctc', 15, 2)->after('base_amount')->nullable();
            $table->decimal('annual_ctc', 15, 2)->after('monthly_ctc')->nullable();
            $table->boolean('pf_eligible')->default(true)->after('annual_ctc');
            $table->boolean('esi_eligible')->default(true)->after('pf_eligible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary_assignments', function (Blueprint $table) {
            $table->dropColumn(['monthly_ctc', 'annual_ctc', 'pf_eligible', 'esi_eligible']);
        });
    }
};
