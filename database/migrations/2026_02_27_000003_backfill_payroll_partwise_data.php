<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
     /**
      * Run the migrations.
      */
     public function up(): void
     {
          // Backfill payroll_entries: Map existing totals to Part 1
          DB::table('payroll_entries')->update([
               'is_part_wise' => false,
               'part1_gross' => DB::raw('gross_salary'),
               'part1_deductions' => DB::raw('total_deductions'),
               'part1_net' => DB::raw('net_salary'),
               'part2_gross' => 0,
               'part2_deductions' => 0,
               'part2_net' => 0,
          ]);

          // Backfill payroll_components: Default all existing components to Part 1
          DB::table('payroll_components')->update([
               'part_number' => 1
          ]);
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
          // No need to reverse the data backfill as the columns remain (or are dropped by other migrations)
     }
};
