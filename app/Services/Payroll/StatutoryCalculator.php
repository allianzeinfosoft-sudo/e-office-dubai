<?php

namespace App\Services\Payroll;

use App\Models\Setting;

class StatutoryCalculator
{
     /**
      * Calculate Employee Provident Fund (PF) contribution.
      */
     public function calculatePF($basicSalary, $calculatedComponents = [], $grossSalary = 0, $isEmployer = false, $standardBasic = 0, $hasPFNo = true)
     {
          // Calculation no longer strictly requires PF number to be present if employee is eligible
          // if (!$hasPFNo) { return 0; }
          $limit = Setting::get('payroll_pf_wage_limit', 15000);
          $percent = Setting::get($isEmployer ? 'payroll_pf_employer_percent' : 'payroll_pf_employee_percent', 12);
          $baseConfig = Setting::get($isEmployer ? 'payroll_pf_employer_base' : 'payroll_pf_employee_base', 'Basic Salary');
          $useFixed = $isEmployer ? Setting::get('payroll_pf_employer_use_fixed', 1) : 0;

          $base = 0;
          if (strtolower($baseConfig) === 'gross') {
               foreach ($calculatedComponents as $comp) {
                    if ($comp['type'] === 'earning' && ($comp['part_number'] == 1 || ($comp['is_ctc_variable'] ?? false))) {
                         $base += ($isEmployer && $useFixed) ? $comp['standard_amount'] : $comp['amount'];
                    }
               }
          } else {
               $baseComponents = array_map('trim', explode(',', strtolower($baseConfig)));

               // If it's Employer and useFixed is on, we look for Standard amounts
               if ($isEmployer && $useFixed) {
                    // Check if it's just 'basic salary'
                    if (count($baseComponents) === 1 && (in_array('basic salary', $baseComponents) || in_array('basic', $baseComponents))) {
                         $base = $standardBasic;
                    } else {
                         foreach ($calculatedComponents as $comp) {
                              if (in_array(strtolower($comp['name']), $baseComponents)) {
                                   $base += $comp['standard_amount'];
                              }
                         }
                    }
               } else {
                    // Default logic (Earned amounts)
                    if (count($baseComponents) === 1 && (in_array('basic salary', $baseComponents) || in_array('basic', $baseComponents))) {
                         $base = $basicSalary;
                    } else {
                         foreach ($calculatedComponents as $comp) {
                              if (in_array(strtolower($comp['name']), $baseComponents)) {
                                   $base += $comp['amount'];
                              }
                         }
                    }
               }
          }

          $base = min($base, $limit);

          $calculationBase = $base;
          return round($calculationBase * ($percent / 100));
     }

     public function calculateESI($grossSalary, $calculatedComponents = [], $isEmployer = false, $hasESINo = true)
     {
          $limit = Setting::get('payroll_esi_wage_limit', 21000);
          $percent = Setting::get($isEmployer ? 'payroll_esi_employer_percent' : 'payroll_esi_employee_percent', 0.75);
          $baseConfig = Setting::get($isEmployer ? 'payroll_esi_employer_base' : 'payroll_esi_employee_base', 'Gross');
          $useFixed = $isEmployer ? Setting::get('payroll_esi_employer_use_fixed', 0) : 0;

          $base = 0;
          if (strtolower($baseConfig) === 'gross') {
               foreach ($calculatedComponents as $comp) {
                    if ($comp['type'] === 'earning' && ($comp['part_number'] == 1 || ($comp['is_ctc_variable'] ?? false))) {
                         $base += ($isEmployer && $useFixed) ? $comp['standard_amount'] : $comp['amount'];
                    }
               }
          } else {
               $baseComponents = array_map('trim', explode(',', strtolower($baseConfig)));
               foreach ($calculatedComponents as $comp) {
                    $compName = strtolower($comp['name']);
                    // Robust matching for Basic Salary components
                    if (in_array($compName, $baseComponents) || 
                        (str_contains($compName, 'basic') && in_array('basic salary', $baseComponents)) ||
                        (str_contains($compName, 'basic+da') && in_array('basic salary', $baseComponents))) {
                         $base += ($isEmployer && $useFixed) ? $comp['standard_amount'] : $comp['amount'];
                    }
               }
          }

          // ESI Base Ceiling: In many setups, ESI is capped at the wage limit for calculation, 
          // although technically ESI calculation is on actual wages for those below the limit.
          // We cap it consistent with the frontend logic to avoid discrepancies.
          $base = min($base, $limit);

          return round($base * ($percent / 100)); // Statutory ESI rounded to nearest rupee based on 50 paise rule
     }

     /**
      * Calculate Professional Tax (PT).
      * Example slab (Adjust based on state rules).
      */
     public function calculatePT($grossSalary)
     {
          if ($grossSalary <= 15000) {
               return 0;
          } elseif ($grossSalary <= 20000) {
               return 150;
          } else {
               return 200;
          }
     }
}
