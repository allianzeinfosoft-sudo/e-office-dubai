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
          Schema::table('payroll_entries', function (Blueprint $table) {
               $table->boolean('is_part_wise')->default(false)->after('attendance_days');
               $table->decimal('part1_gross', 15, 2)->default(0)->after('is_part_wise');
               $table->decimal('part1_deductions', 15, 2)->default(0)->after('part1_gross');
               $table->decimal('part1_net', 15, 2)->default(0)->after('part1_deductions');
               $table->decimal('part2_gross', 15, 2)->default(0)->after('part1_net');
               $table->decimal('part2_deductions', 15, 2)->default(0)->after('part2_gross');
               $table->decimal('part2_net', 15, 2)->default(0)->after('part2_deductions');
          });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
          Schema::table('payroll_entries', function (Blueprint $table) {
               $table->dropColumn([
                    'is_part_wise',
                    'part1_gross',
                    'part1_deductions',
                    'part1_net',
                    'part2_gross',
                    'part2_deductions',
                    'part2_net'
               ]);
          });
     }
};
