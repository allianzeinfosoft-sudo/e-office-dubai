<?php

namespace App\Services\Payroll;

use App\Models\Employee;
use App\Models\PayrollBatch;
use App\Models\PayrollEntry;
use App\Models\PayrollComponent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollService
{
     protected $calculator;

     public function __construct(SalaryCalculator $calculator)
     {
          $this->calculator = $calculator;
     }

     /**
      * Generate a new payroll batch for a given month and year.
      */
     public function generateBatch($month, $year, $departmentId = null, $isPartWise = false, $salaryStructureId = null)
     {
          return DB::transaction(function () use ($month, $year, $departmentId, $isPartWise, $salaryStructureId) {
               $batchDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

               // Check if batch already exists for this period/dept/structure
               $query = PayrollBatch::where('month', $month)
                    ->where('year', $year);
               
               if ($salaryStructureId) {
                    $query->where('salary_structure_id', $salaryStructureId);
               }
               
               if ($departmentId) {
                    $query->where('department_id', $departmentId);
               }

               $existing = $query->first();

               if ($existing) {
                    return $existing;
               }

               $batch = PayrollBatch::create([
                    'month' => $month,
                    'year' => $year,
                    'department_id' => $departmentId,
                    'salary_structure_id' => $salaryStructureId,
                    'is_part_wise' => $isPartWise,
                    'status' => 'draft',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
               ]);

               $employees = Employee::whereHas('userStatus', function ($q) {
                    $q->where('id', 1); // Active
               });

               if ($salaryStructureId) {
                    $employees->whereHas('salaryAssignments', function($q) use ($batchDate, $salaryStructureId) {
                         $q->where('effective_date', '<=', $batchDate)
                           ->where('salary_structure_id', $salaryStructureId)
                           ->whereIn('id', function($sub) use ($batchDate) {
                               $sub->select(\DB::raw('MAX(id)'))
                                   ->from('employee_salary_assignments')
                                   ->where('effective_date', '<=', $batchDate)
                                   ->groupBy('employee_id');
                           });
                    });
               }

               if ($departmentId) {
                    $employees->where('department_id', $departmentId);
               }

               $employees = $employees->get();

               foreach ($employees as $employee) {
                    $this->processEmployeeEntry($batch, $employee);
               }

               return $batch;
          });
     }

     /**
      * Process individual payroll entry for an employee.
      */
     protected function processEmployeeEntry(PayrollBatch $batch, Employee $employee)
     {
          $calcData = $this->calculator->calculate($employee, $batch->month, $batch->year, $batch->is_part_wise);

          if (!$calcData) {
               return;
          }

          $entry = $batch->entries()->create([
               'employee_id' => $employee->id,
               'gross_salary' => $calcData['gross_salary'],
               'total_deductions' => $calcData['total_deductions'],
               'net_salary' => $calcData['net_salary'],
               'total_employer_contribution' => $calcData['total_employer_contribution'] ?? 0,
               'ctc' => $calcData['ctc'] ?? 0,
               'lop_days' => $calcData['lop_days'],
               'lop_amount' => $calcData['lop_amount'],
               'attendance_days' => 30 - $calcData['lop_days'], // Use 30 as standard for e-office pro-rata
               'is_part_wise' => $calcData['is_part_wise'] ?? false,
               'part1_gross' => $calcData['part1_gross'] ?? 0,
               'part1_deductions' => $calcData['part1_deductions'] ?? 0,
               'part1_net' => $calcData['part1_net'] ?? 0,
               'part2_gross' => $calcData['part2_gross'] ?? 0,
               'part2_deductions' => $calcData['part2_deductions'] ?? 0,
               'part2_net' => $calcData['part2_net'] ?? 0,
          ]);

          foreach ($calcData['components'] as $component) {
               $entry->components()->create([
                    'component_name' => $component['name'],
                    'type' => $component['type'],
                    'is_ctc_variable' => $component['is_ctc_variable'] ?? false,
                    'is_attendance_based' => $component['is_attendance_based'] ?? false,
                    'standard_amount' => $component['standard_amount'] ?? 0,
                    'amount' => $component['amount'],
                    'part_number' => $component['part_number'] ?? 1,
               ]);
          }
     }

     /**
      * Refresh an existing payroll batch based on assignments at that time.
      */
     public function refreshBatch(PayrollBatch $batch)
     {
          return DB::transaction(function () use ($batch) {
               $batchDate = \Carbon\Carbon::create($batch->year, $batch->month, 1)->endOfMonth();

               // 1. Update existing entries and remove ineligible ones
               foreach ($batch->entries as $entry) {
                    $employee = $entry->employee;
                    
                    $calcData = $this->calculator->calculate($employee, $batch->month, $batch->year, $batch->is_part_wise);
                    
                    // Re-check assignment for THIS specific batch/structure/date
                    $assignment = $employee->getAssignmentForDate($batchDate);
                    
                    if (!$calcData || !$assignment || ($batch->salary_structure_id && $assignment->salary_structure_id != $batch->salary_structure_id)) {
                         // If no longer eligible, assigned elsewhere, or inactive, remove entry
                         $entry->components()->delete();
                         $entry->delete();
                         continue;
                    }

                    // Delete old components
                    $entry->components()->delete();

                    // Update entry with fresh data
                    $entry->update([
                         'gross_salary' => $calcData['gross_salary'],
                         'total_deductions' => $calcData['total_deductions'],
                         'net_salary' => $calcData['net_salary'],
                         'total_employer_contribution' => $calcData['total_employer_contribution'] ?? 0,
                         'ctc' => $calcData['ctc'] ?? 0,
                         'lop_days' => $calcData['lop_days'],
                         'lop_amount' => $calcData['lop_amount'],
                         'attendance_days' => 30 - $calcData['lop_days'],
                         'part1_gross' => $calcData['part1_gross'] ?? 0,
                         'part1_deductions' => $calcData['part1_deductions'] ?? 0,
                         'part1_net' => $calcData['part1_net'] ?? 0,
                         'part2_gross' => $calcData['part2_gross'] ?? 0,
                         'part2_deductions' => $calcData['part2_deductions'] ?? 0,
                         'part2_net' => $calcData['part2_net'] ?? 0,
                    ]);

                    // Re-create components
                    foreach ($calcData['components'] as $component) {
                         $entry->components()->create([
                              'component_name' => $component['name'],
                              'type' => $component['type'],
                              'is_ctc_variable' => $component['is_ctc_variable'] ?? false,
                              'is_attendance_based' => $component['is_attendance_based'] ?? false,
                              'standard_amount' => $component['standard_amount'] ?? 0,
                              'amount' => $component['amount'],
                              'part_number' => $component['part_number'] ?? 1,
                         ]);
                    }
               }

               // 2. Add missing employees who should be in this batch structure for this period
               if ($batch->salary_structure_id) {
                    $eligibleEmployees = Employee::whereHas('salaryAssignments', function($q) use ($batchDate, $batch) {
                        $q->where('effective_date', '<=', $batchDate)
                          ->where('salary_structure_id', $batch->salary_structure_id)
                          ->whereIn('id', function($sub) use ($batchDate) {
                              $sub->select(\DB::raw('MAX(id)'))
                                  ->from('employee_salary_assignments')
                                  ->where('effective_date', '<=', $batchDate)
                                  ->groupBy('employee_id');
                          });
                    })->whereDoesntHave('payrollEntries', function($q) use ($batch) {
                        $q->where('batch_id', $batch->id);
                    });
                    
                    if ($batch->department_id) {
                        $eligibleEmployees->where('department_id', $batch->department_id);
                    }
                    
                    foreach ($eligibleEmployees->get() as $employee) {
                        $this->processEmployeeEntry($batch, $employee);
                    }
               }

               return $batch;
          });
     }
}
