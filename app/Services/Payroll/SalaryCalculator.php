<?php

namespace App\Services\Payroll;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Attendance;
use Carbon\Carbon;

class SalaryCalculator
{
     protected $statutory;

     public function __construct(StatutoryCalculator $statutory)
     {
          $this->statutory = $statutory;
     }

     /**
      * Calculate payroll for an employee for a specific month/year.
      */
     public function calculate(Employee $employee, $month, $year, $isPartWise = false)
     {
          $batchDate = Carbon::create($year, $month, 1)->endOfMonth();
          $assignment = $employee->getAssignmentForDate($batchDate);
          
          if (!$assignment || !$assignment->structure) {
               return null;
          }

          $baseAmount = $assignment->base_amount;
          $structure = $assignment->structure;
          $structure->load('components');

          $calcComponents = [];
          $totalEarnings = 0;
          $totalDeductions = 0;

          $part1Gross = 0;
          $part1Deductions = 0;
          $part2Gross = 0;
          $part2Deductions = 0;

          // Calculate pro-ration factor
          $workingDays = 30; // Calendar days basis as requested
          $lopDays = $this->calculateLOPDays($employee, $month, $year);
          $payableDays = $workingDays - $lopDays;
          $proRataFactor = $workingDays > 0 ? ($payableDays / $workingDays) : 0;

          $totalEmployerContribution = 0;

          // Ensure Basic Salary is in components if not already part of the structure
          $hasBasic = $structure->components->contains(function ($c) {
               $name = strtolower($c->name);
               return str_contains($name, 'basic') || $name === 'da' || str_contains($name, 'basic+da');
          });

          $actualBasic = round($baseAmount * $proRataFactor, 2);

          if (!$hasBasic) {
               // Pro-rate Basic Salary (it is almost always attendance based)
               $calcComponents[] = [
                    'name' => 'Basic Salary',
                    'type' => 'earning',
                    'is_ctc_variable' => true,
                    'is_attendance_based' => true,
                    'standard_amount' => $baseAmount,
                    'amount' => $actualBasic,
                    'part_number' => 1
               ];
               $totalEarnings += $actualBasic;
               $part1Gross += $actualBasic;
          }

          // Pass 1: Handle Earnings
          foreach ($structure->components->where('type', 'earning') as $component) {
               $standardAmount = 0;
               $actualAmount = 0;
               $calcType = $component->pivot->calculation_type;
               
               // Use value from assignment if specifically set there
               $assignComp = $assignment->components->where('id', $component->id)->first();
               $val = ($assignComp && isset($assignComp->pivot->amount)) ? $assignComp->pivot->amount : $component->pivot->value;

               if ($calcType == 'fixed') {
                    $standardAmount = $val;
                    $actualAmount = $val;
               } elseif ($calcType == 'percentage') {
                    $standardAmount = round($baseAmount * ($val / 100), 2);
                    $actualAmount = $standardAmount;
               } elseif ($calcType == 'earned_percentage') {
                    $standardAmount = round($baseAmount * ($val / 100), 2);
                    $actualAmount = round($actualBasic * ($val / 100), 2);
               } elseif ($calcType == 'percentage_ctc') {
                    $monthlyCTC = $assignment->monthly_ctc ?? 0;
                    $standardAmount = round($monthlyCTC * ($val / 100), 2);
                    $actualAmount = $standardAmount;
               }

               $lowercaseName = strtolower($component->name);
               $shouldProRate = in_array($lowercaseName, ['basic salary', 'da', 'hra', 'incentive', 'incentives']) || $component->is_attendance_based;

               if ($shouldProRate && $calcType != 'earned_percentage') {
                    $actualAmount = round($standardAmount * $proRataFactor, 2);
                    
                    // Round to whole numbers for specific components as requested
                    if (str_contains($lowercaseName, 'basic') || $lowercaseName === 'da' || str_contains($lowercaseName, 'basic+da') || $lowercaseName === 'hra') {
                         $actualAmount = round($actualAmount);
                    }
               }

               if (str_contains($lowercaseName, 'basic') || $lowercaseName === 'da' || str_contains($lowercaseName, 'basic+da')) {
                    $actualAmount = round($actualBasic);
               }

               $partNumber = $component->is_statutory ? 1 : 2;

               $calcComponents[] = [
                    'name' => $component->name,
                    'type' => $component->type,
                    'is_ctc_variable' => (bool) $component->is_ctc_variable,
                    'is_attendance_based' => (bool) $component->is_attendance_based,
                    'standard_amount' => $standardAmount,
                    'amount' => $actualAmount,
                    'part_number' => $partNumber
               ];

               $totalEarnings += $actualAmount;
               if ($partNumber == 1)
                    $part1Gross += $actualAmount;
               else
                    $part2Gross += $actualAmount;
          }

          $grossSalary = $totalEarnings;

          // Pass 2: Handle Deductions and Employer Contributions (including statutory)
          $otherComponents = $structure->components->whereIn('type', ['deduction', 'employer_contribution']);
          foreach ($otherComponents as $component) {
               $standardAmount = 0;
               $actualAmount = 0;
               $calcType = $component->pivot->calculation_type;
               
               // Use value from assignment if specifically set there
               $assignComp = $assignment->components->where('id', $component->id)->first();
               $val = ($assignComp && isset($assignComp->pivot->amount)) ? $assignComp->pivot->amount : $component->pivot->value;
               
               $lowercaseName = strtolower($component->name);

               // Use Statutory Calculator for PF/ESI if it's marked as statutory
               if ($component->is_statutory && (str_contains($lowercaseName, 'pf') || str_contains($lowercaseName, 'esi'))) {
                    $isEmployer = ($component->type === 'employer_contribution');
                    if (str_contains($lowercaseName, 'pf')) {
                         if ($assignment->pf_eligible) {
                              $actualAmount = $this->statutory->calculatePF($actualBasic, $calcComponents, $grossSalary, $isEmployer, $baseAmount, $employee->pf_no);
                              $standardAmount = $this->statutory->calculatePF($baseAmount, $calcComponents, $baseAmount, $isEmployer, $baseAmount, $employee->pf_no);
                         } else {
                              $actualAmount = 0;
                              $standardAmount = 0;
                         }
                    } else {
                         if ($assignment->esi_eligible) {
                              $actualAmount = $this->statutory->calculateESI($grossSalary, $calcComponents, $isEmployer, $employee->esi_no);
                              $standardAmount = $this->statutory->calculateESI($grossSalary, $calcComponents, $isEmployer, $employee->esi_no);
                         } else {
                              $actualAmount = 0;
                              $standardAmount = 0;
                         }
                    }
               } else {
                    // Regular calculation
                    if ($calcType == 'fixed') {
                         $standardAmount = $val;
                         $actualAmount = $val;
                    } elseif ($calcType == 'percentage') {
                         $standardAmount = round($baseAmount * ($val / 100), 2);
                         $actualAmount = $standardAmount;
                    } elseif ($calcType == 'earned_percentage') {
                         $standardAmount = round($baseAmount * ($val / 100), 2);
                         $actualAmount = round($actualBasic * ($val / 100), 2);
                    } elseif ($calcType == 'percentage_ctc') {
                         $monthlyCTC = $assignment->monthly_ctc ?? 0;
                         $standardAmount = round($monthlyCTC * ($val / 100), 2);
                         $actualAmount = $standardAmount;
                    }

                    if ($component->is_attendance_based && $calcType != 'earned_percentage') {
                         $actualAmount = round($standardAmount * $proRataFactor, 2);
                    }
               }

               $partNumber = $component->is_statutory ? 1 : 2;

               $calcComponents[] = [
                    'name' => $component->name,
                    'type' => $component->type,
                    'is_ctc_variable' => (bool) $component->is_ctc_variable,
                    'is_attendance_based' => (bool) $component->is_attendance_based,
                    'standard_amount' => $standardAmount,
                    'amount' => $actualAmount,
                    'part_number' => $partNumber
               ];

               if ($component->type == "deduction") {
                    $totalDeductions += $actualAmount;
                    if ($isPartWise && str_contains(strtolower($component->name), "esi")) {
                         $esiRate = \App\Models\Setting::get("payroll_esi_employee_percent", 0.75) / 100;
                         $esiWageLimit = \App\Models\Setting::get("payroll_esi_wage_limit", 21000);
                         $p1EsiSalary = ($assignment->esi_eligible) ? min($part1Gross, $esiWageLimit) : 0;
                         $p1Esi = ($assignment->esi_eligible) ? round($p1EsiSalary * $esiRate) : 0;
                         $p2Esi = max(0, $actualAmount - $p1Esi);
                         $part1Deductions += $p1Esi;
                         $part2Deductions += $p2Esi;
                    } else {
                         if ($partNumber == 1)
                              $part1Deductions += $actualAmount;
                         else
                              $part2Deductions += $actualAmount;
                    }
               } elseif ($component->type == 'employer_contribution') {
                    $totalEmployerContribution += $actualAmount;
               }
          }

          // No longer adding LOP as a separate deduction row to match the pro-rated earnings design 
          // (LOP is already reflected in reduced actual amounts of earnings)


          // Note: totalEarnings in this context is what we consider Gross for this structure
          $grossSalary = $totalEarnings;
          $netSalary = $grossSalary - $totalDeductions;

          $lopAmount = round(($baseAmount / $workingDays) * $lopDays, 2);

          $ctcBase = 0;
          $ctcEmployer = 0;

          foreach ($calcComponents as $comp) {
               // Include in CTC if it's Basic Salary/HRA/DA
               if ($comp['type'] == 'earning' && (str_contains(strtolower($comp['name']), 'basic') || str_contains(strtolower($comp['name']), 'hra') || strtolower($comp['name']) == 'da')) {
                    $ctcBase += $comp['standard_amount'];
               }
               // For employer contributions, always include their standard amount in CTC
               elseif ($comp['type'] == 'employer_contribution') {
                    $ctcEmployer += $comp['standard_amount'];
               }
          }
          $totalCTC = $ctcBase + $ctcEmployer;

          return [
               'employee_id' => $employee->id,
               'is_part_wise' => $isPartWise,
               'gross_salary' => $grossSalary,
               'total_deductions' => $totalDeductions,
               'net_salary' => $netSalary,
               'total_employer_contribution' => $totalEmployerContribution,
               'ctc' => $totalCTC,
               'part1_gross' => $part1Gross,
               'part1_deductions' => $part1Deductions,
               'part1_net' => $part1Gross - $part1Deductions,
               'part2_gross' => $part2Gross,
               'part2_deductions' => $part2Deductions,
               'part2_net' => $part2Gross - $part2Deductions,
               'lop_days' => $lopDays,
               'lop_amount' => $lopAmount,
               'components' => $calcComponents
          ];
     }

     /**
      * Calculate LOP days based on leaves and allocation balance.
      * Logic: 
      * 1. Get total leaves taken in the target month (Approved, non-off-day).
      * 2. Get yearly allocation and total leaves taken in the year up to the end of target month.
      * 3. LOP = portion of month's leaves that fall beyond the yearly allocation.
      */
     public function calculateLOPDays(Employee $employee, $month, $year)
     {
          $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
          $endOfMonth = $startOfMonth->copy()->endOfMonth();
          $startOfYear = $startOfMonth->copy()->startOfYear();

          // 1. Get total leaves taken in the target month
          $monthTaken = Leave::where('user_id', $employee->user_id)
               ->where('status', 2) // Approved
               ->where('leave_type', '!=', 'off_day')
               ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('leave_from', [$startOfMonth, $endOfMonth])
                         ->orWhereBetween('leave_to', [$startOfMonth, $endOfMonth])
                         ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                              $q->where('leave_from', '<=', $startOfMonth)
                                   ->where('leave_to', '>=', $endOfMonth);
                         });
               })
               ->get()
               ->sum(function ($leave) use ($startOfMonth, $endOfMonth) {
                    if ($leave->leave_type === 'half_day')
                         return 0.5;
                    $from = Carbon::parse($leave->leave_from)->max($startOfMonth);
                    $to = Carbon::parse($leave->leave_to)->min($endOfMonth);
                    return $from->diffInDays($to) + 1;
               });

          // 2. Get yearly taken leaves until end of target month
          $yearlyTakenUntilEnd = Leave::where('user_id', $employee->user_id)
               ->where('status', 2)
               ->where('leave_type', '!=', 'off_day')
               ->where(function ($query) use ($startOfYear, $endOfMonth) {
                    $query->whereBetween('leave_from', [$startOfYear, $endOfMonth])
                         ->orWhereBetween('leave_to', [$startOfYear, $endOfMonth]);
               })
               ->get()
               ->sum(function ($leave) use ($startOfYear, $endOfMonth) {
                    if ($leave->leave_type === 'half_day')
                         return 0.5;
                    $from = Carbon::parse($leave->leave_from)->max($startOfYear);
                    $to = Carbon::parse($leave->leave_to)->min($endOfMonth);
                    return $from->diffInDays($to) + 1;
               });

          // 3. Get Allocation for the year
          $allocation = \App\Models\LeaveAllocation::where('user_id', $employee->user_id)
               ->where('year', $year)
               ->value('total_leaves') ?? 0;

          // 4. Calculate balance at the end of the month
          $balanceAtEnd = $allocation - $yearlyTakenUntilEnd;

          // 5. Calculate LOP (days that exceeded balance this month)
          $lopDays = max(0, min($monthTaken, -$balanceAtEnd));

          return $lopDays;
     }
}
