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
        // Using raw SQL because ENUM changes are problematic with Schema::table in some MySQL/Laravel versions
        DB::statement("ALTER TABLE salary_components MODIFY COLUMN type ENUM('earning', 'deduction', 'employer_contribution')");
        DB::statement("ALTER TABLE payroll_components MODIFY COLUMN type ENUM('earning', 'deduction', 'employer_contribution')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE salary_components MODIFY COLUMN type ENUM('earning', 'deduction')");
        DB::statement("ALTER TABLE payroll_components MODIFY COLUMN type ENUM('earning', 'deduction')");
    }
};
