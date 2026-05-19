<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollBatch;
use App\Models\Department;
use App\Models\SalaryStructure;
use App\Models\Employee;
use App\Models\PayrollEntry;
use App\Exports\SalaryRegisterExport;
use App\Exports\SalaryPayFileExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollSalaryRegisterController extends Controller
{
    public function index(Request $request)
    {
        $structures = SalaryStructure::all();
        $years = PayrollBatch::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $salaryType = $request->input('salary_type', 'all');
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with(['employee.department', 'employee.salaryAssignments.structure', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)
                  ->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $registerData = $this->formatRegisterData($entries, $salaryType);

        return view('payroll.reports.salary_register', compact(
            'structures', 'years', 'month', 'year', 'salaryType', 'structureId', 'registerData'
        ));
    }

    public function formXiIndex(Request $request)
    {
        $structures = SalaryStructure::all();
        $years = PayrollBatch::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with([
            'employee.department', 
            'employee.salaryAssignments.structure', 
            'employee.statutoryDetails',
            'components', 
            'batch'
        ])->whereHas('batch', function($q) use ($month, $year) {
            $q->where('month', $month)
              ->where('year', $year);
        });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $formXiData = $this->formatFormXiData($entries);

        if ($request->input('export') === 'excel') {
            return Excel::download(new \App\Exports\FormXiExport($formXiData, $month, $year), 'Form_XI_Register_of_Wages.xlsx');
        }

        $companyName = \App\Models\Setting::get('company_name', 'ATS');
        $branch = "Main Office"; // Fallback

        return view('payroll.reports.form_xi', compact(
            'structures', 'years', 'month', 'year', 'structureId', 'formXiData', 'companyName', 'branch'
        ));
    }

    private function formatRegisterData($entries, $salaryType = 'all')
    {
        $pfBaseConfig = \App\Models\Setting::get('payroll_pf_employee_base', 'Basic Salary');
        $pfBaseComponents = array_map('trim', explode(',', strtolower($pfBaseConfig)));

        $esiBaseConfig = \App\Models\Setting::get('payroll_esi_employee_base', 'Gross');
        $esiBaseComponents = array_map('trim', explode(',', strtolower($esiBaseConfig)));

        // Get Statutory Components from Master
        $statutoryComponents = \App\Models\SalaryComponent::where('is_statutory', 1)->pluck('name')->toArray();
        $statutoryComponents = array_unique(array_merge($statutoryComponents, ['Basic', 'DA', 'HRA', 'Basic Salary', 'Basic+DA', 'Deamess Allowance', 'PF', 'ESI', 'Provident Fund', 'Insurance']));

        // Group the entries by Salary Scheme
        $grouped = $entries->groupBy(function ($entry) {
            return $entry->employee->currentSalaryAssignment ? $entry->employee->currentSalaryAssignment->structure->name : 'Unassigned';
        });

        $registerData = [];

        foreach ($grouped as $schemeName => $schemeEntries) {
            $schemeEarnings = [];
            $schemeDeductions = [];

            // Determine unique additions and deductions for this scheme
            foreach ($schemeEntries as $entry) {
                $additions = $entry->components->where('type', 'earning')
                    ->whereNotIn('component_name', ['Basic', 'DA', 'HRA', 'Basic Salary', 'Basic+DA', 'Deamess Allowance', 'House Rent Allowance'])->pluck('component_name')->toArray();
                $schemeEarnings = array_unique(array_merge($schemeEarnings, $additions));

                $deductions = $entry->components->where('type', 'deduction')
                    ->whereNotIn('component_name', ['PF', 'Provident Fund', 'ESI', 'Insurance', 'TDS', 'Tax Deducted'])->pluck('component_name')->toArray();
                $schemeDeductions = array_unique(array_merge($schemeDeductions, $deductions));
            }

            sort($schemeEarnings);
            sort($schemeDeductions);

            $rows = $schemeEntries->values()->map(function ($entry, $index) use ($schemeEarnings, $schemeDeductions, $pfBaseConfig, $pfBaseComponents, $esiBaseConfig, $esiBaseComponents, $statutoryComponents, $salaryType, $schemeName) {
                $emp = $entry->employee;
                $components = $entry->components;

                // Split components for Part A / Part B
                $statEarnings = 0;
                $statDeductions = 0;
                $nonStatEarnings = 0;
                $nonStatDeductions = 0;

                if ($entry->is_part_wise) {
                    $statEarnings = $entry->part1_gross;
                    $statDeductions = $entry->part1_deductions;
                    $nonStatEarnings = $entry->part2_gross;
                    $nonStatDeductions = $entry->part2_deductions;
                } else {
                    foreach ($components as $comp) {
                        $isStat = in_array($comp->component_name, $statutoryComponents);
                        if ($comp->type === 'earning') {
                            if ($isStat) $statEarnings += $comp->amount;
                            else $nonStatEarnings += $comp->amount;
                        } elseif ($comp->type === 'deduction') {
                            if ($isStat) $statDeductions += $comp->amount;
                            else $nonStatDeductions += $comp->amount;
                        }
                    }
                    $pfAttr = $entry->pf_amount ?? 0;
                    $esiAttr = $entry->esi_amount ?? 0;
                    if ($components->where('component_name', 'PF')->count() == 0) $statDeductions += $pfAttr;
                    if ($components->where('component_name', 'ESI')->count() == 0) $statDeductions += $esiAttr;
                }
                // Dynamic values based on filter
                $displayStatEarnings = $statEarnings;
                $displayStatDeductions = $statDeductions;
                $displayNonStatEarnings = $nonStatEarnings;
                $displayNonStatDeductions = $nonStatDeductions;

                if ($salaryType === 'statutory' || $salaryType === 'pf') {
                    $displayNonStatEarnings = 0;
                    $displayNonStatDeductions = 0;
                } elseif ($salaryType === 'non_statutory' || $salaryType === 'non_pf') {
                    $displayStatEarnings = 0;
                    $displayStatDeductions = 0;
                }
                $partANet = $displayStatEarnings - $displayStatDeductions;
                $partBNet = $displayNonStatEarnings - $displayNonStatDeductions;

                $pf = 0;
                $esi = 0;
                if ($salaryType !== 'non_statutory' && $salaryType !== 'non_pf') {
                    $pf = $components->where('component_name', 'PF')->sum('amount');
                    $esi = $components->where('component_name', 'ESI')->sum('amount');
                    if (!$entry->is_part_wise) {
                        if ($pf == 0) $pf = $entry->pf_amount ?? 0;
                        if ($esi == 0) $esi = $entry->esi_amount ?? 0;
                    }
                }

                $pfWage = 0;
                $pfLimit = \App\Models\Setting::get('payroll_pf_wage_limit', 15000);
                if ($pf > 0) {
                    if (strtolower($pfBaseConfig) === 'gross') {
                        $pfWage = $statEarnings;
                    } else {
                        $pfWage = $components->where('type', 'earning')
                            ->filter(function($comp) use ($pfBaseComponents) {
                                return in_array(strtolower($comp->component_name), $pfBaseComponents);
                            })->sum('amount');
                    }
                    $pfWage = round($pfWage);
                    if ($pfWage > $pfLimit && $pfLimit > 0) $pfWage = $pfLimit;
                }

                $esiWage = 0;
                $esiLimit = \App\Models\Setting::get('payroll_esi_wage_limit', 21000);
                if ($esi > 0) {
                    if (strtolower($esiBaseConfig) === 'gross') {
                        $esiWage = $statEarnings;
                    } else {
                        $esiWage = $components->where('type', 'earning')
                            ->filter(function($comp) use ($esiBaseComponents) {
                                return in_array(strtolower($comp->component_name), $esiBaseComponents);
                            })->sum('amount');
                    }
                    $esiWage = round($esiWage);
                    if ($esiWage > $esiLimit && $esiLimit > 0) $esiWage = $esiLimit;
                }

                // Minimum/Actual Wage
                $minBasicDA = ($salaryType === 'non_statutory' || $salaryType === 'non_pf') ? 0 : $components->whereIn('component_name', ['Basic', 'DA', 'Basic Salary', 'Basic+DA', 'Deamess Allowance'])->sum('standard_amount');
                $minHRA = ($salaryType === 'non_statutory' || $salaryType === 'non_pf') ? 0 : $components->whereIn('component_name', ['HRA', 'House Rent Allowance'])->sum('standard_amount');
                
                $actualBasicDA = ($salaryType === 'non_statutory' || $salaryType === 'non_pf') ? 0 : round($components->whereIn('component_name', ['Basic', 'DA', 'Basic Salary', 'Basic+DA', 'Deamess Allowance'])->sum('amount'));
                $actualHRA = ($salaryType === 'non_statutory' || $salaryType === 'non_pf') ? 0 : round($components->whereIn('component_name', ['HRA', 'House Rent Allowance'])->sum('amount'));

                // Breakdown components
                $dynamicEarnings = [];
                foreach ($schemeEarnings as $earnName) {
                    $val = $components->where('component_name', $earnName)->sum('amount');
                    $isStat = in_array($earnName, $statutoryComponents);
                    if ($salaryType === 'statutory' || $salaryType === 'pf') {
                        if (!$isStat) $val = 0;
                    } elseif ($salaryType === 'non_statutory' || $salaryType === 'non_pf') {
                        if ($isStat) $val = 0;
                    }
                    $dynamicEarnings[$earnName] = $val;
                }

                $dynamicDeductions = [];
                foreach ($schemeDeductions as $dedName) {
                    $val = $components->where('component_name', $dedName)->sum('amount');
                    $isStat = in_array($dedName, $statutoryComponents);
                    if ($salaryType === 'statutory' || $salaryType === 'pf') {
                        if (!$isStat) $val = 0;
                    } elseif ($salaryType === 'non_statutory' || $salaryType === 'non_pf') {
                        if ($isStat) $val = 0;
                    }
                    $dynamicDeductions[$dedName] = $val;
                }

                $totalGross = $displayStatEarnings + $displayNonStatEarnings;
                $totalDed = $displayStatDeductions + $displayNonStatDeductions;
                $net = $totalGross - $totalDed;

                return [
                    'sl_no' => $index + 1,
                    'emp_code' => $emp->employeeID,
                    'account_number' => $emp->account_number ?? '',
                    'ifsc' => $emp->ifsc ?? '',
                    'bank' => $emp->bank_name ?? '',
                    'company' => 'ATS', 
                    'salary_scheme' => 'Y',
                    'name' => $emp->full_name,

                    'min_basic_da' => $minBasicDA,
                    'min_hra' => $minHRA,
                    'min_total_payable' => $minBasicDA + $minHRA,

                    'actual_basic_da' => $actualBasicDA,
                    'actual_hra' => $actualHRA,
                    'actual_total' => $actualBasicDA + $actualHRA,

                    'payable_days' => ($salaryType === 'non_statutory' || $salaryType === 'non_pf') ? 0 : $entry->attendance_days,
                    'ot' => ($salaryType === 'statutory' || $salaryType === 'pf') ? 0 : $entry->ot_amount, 

                    'gross_payable' => $totalGross,
                    'grand_total' => $totalGross, 

                    'pf_salary' => $pfWage,
                    'esi_salary' => $esiWage,

                    'pf' => $pf,
                    'esi' => $esi,

                    'dynamic_earnings' => $dynamicEarnings,
                    'dynamic_deductions' => $dynamicDeductions,

                    'total_deductions' => $totalDed,
                    'salary_part_a' => $partANet,
                    'salary_part_b' => $partBNet,
                    'round_off' => 0, 
                    'net_payment' => $net,
                    
                    'remarks' => $entry->remarks ?? ''
                ];
            });

            $registerData[$schemeName] = [
                'scheme_name' => $schemeName,
                'earnings_headers' => $schemeEarnings,
                'deductions_headers' => $schemeDeductions,
                'rows' => $rows
            ];
        }

        return $registerData;
    }

    private function formatFormXiData($entries)
    {
        $statutoryComponents = \App\Models\SalaryComponent::where('is_statutory', 1)->pluck('name')->toArray();
        $allComponentsMaster = \App\Models\SalaryComponent::pluck('name')->toArray();

        return $entries->map(function ($entry, $index) use ($statutoryComponents, $allComponentsMaster) {
            $emp = $entry->employee;
            $components = $entry->components;
            $stat = $emp->statutoryDetails;

            // 1. Min Rate
            $minBasicDA = $components->whereIn('component_name', ['Basic', 'DA', 'Basic Salary', 'Basic+DA', 'Deamess Allowance'])->sum('standard_amount');
            $minHRA = $components->whereIn('component_name', ['HRA', 'House Rent Allowance'])->sum('standard_amount');
            
            // 2. Actual Paid
            $actualBasicDA = $components->whereIn('component_name', ['Basic', 'DA', 'Basic Salary', 'Basic+DA', 'Deamess Allowance'])->sum('amount');
            $actualHRA = $components->whereIn('component_name', ['HRA', 'House Rent Allowance'])->sum('amount');

            // 3. Work & Earnings
            // USER REQUEST: Part 2 values no need to show in Form XI
            $ot = 0; 
            $incentive = 0;
            $eric = 0;
            $others = 0;
            
            // 4. Statutory Base (PF/ESI Salary) - Applying Limits
            $pfWage = 0; $esiWage = 0;
            $pfLimit = \App\Models\Setting::get('payroll_pf_wage_limit', 15000);
            $esiLimit = \App\Models\Setting::get('payroll_esi_wage_limit', 21000);

            $pfAmt = $components->whereIn('component_name', ['PF', 'Provident Fund'])->where('type', 'deduction')->sum('amount');
            if ($pfAmt == 0) $pfAmt = $entry->pf_amount ?? 0;
            
            $esiAmt = $components->whereIn('component_name', ['ESI', 'Insurance'])->where('type', 'deduction')->sum('amount');
            if ($esiAmt == 0) $esiAmt = $entry->esi_amount ?? 0;

            if ($pfAmt > 0) {
                $pfBaseConfig = \App\Models\Setting::get('payroll_pf_employee_base', 'Basic Salary');
                $pfBaseComponents = array_map('trim', explode(',', strtolower($pfBaseConfig)));
                if (strtolower($pfBaseConfig) === 'gross') $pfWage = $entry->is_part_wise ? $entry->part1_gross : ($actualBasicDA + $actualHRA);
                else {
                    $pfWage = $components->where('type', 'earning')
                        ->filter(function($comp) use ($pfBaseComponents) {
                            return in_array(strtolower($comp->component_name), $pfBaseComponents);
                        })->sum('amount');
                }
                if ($pfWage > $pfLimit && $pfLimit > 0) $pfWage = $pfLimit;
            }
            if ($esiAmt > 0) {
                $esiBaseConfig = \App\Models\Setting::get('payroll_esi_employee_base', 'Gross');
                if (strtolower($esiBaseConfig) === 'gross') $esiWage = $entry->is_part_wise ? $entry->part1_gross : ($actualBasicDA + $actualHRA);
                else {
                    $esiBaseComponents = array_map('trim', explode(',', strtolower($esiBaseConfig)));
                    $esiWage = $components->where('type', 'earning')
                        ->filter(function($comp) use ($esiBaseComponents) {
                            return in_array(strtolower($comp->component_name), $esiBaseComponents);
                        })->sum('amount');
                }
                if ($esiWage > $esiLimit && $esiLimit > 0) $esiWage = $esiLimit;
            }

            // 5. Deductions
            $pf = $pfAmt;
            $esi = $esiAmt;
            $wwf = $components->where('component_name', 'WWF')->where('type', 'deduction')->sum('amount');
            $pt = $components->whereIn('component_name', ['PT', 'Professional Tax'])->where('type', 'deduction')->sum('amount');
            $tds = $entry->tds_amount ?? $components->whereIn('component_name', ['TDS', 'Tax Deducted'])->where('type', 'deduction')->sum('amount');
            
            $loan = $components->where('component_name', 'Loan')->where('type', 'deduction')->sum('amount');
            $advance = $components->where('component_name', 'Salary Advance')->where('type', 'deduction')->sum('amount');

            $totalDed = $entry->total_deductions;
            $otherDed = $totalDed - ($pf + $esi + $wwf + $pt + $loan + $advance + $tds);
            if ($otherDed < 0) $otherDed = 0;

            // Part 1 Totals
            $part1Gross = $entry->is_part_wise ? $entry->part1_gross : ($actualBasicDA + $actualHRA);
            $part1Net = $entry->is_part_wise ? $entry->part1_net : ($part1Gross - $totalDed);

            return [
                'sl_no' => $index + 1,
                'pf_no' => $stat->pf_no ?? ($emp->pf_no ?? ''),
                'uan' => $emp->uan_no ?? ($stat->uan_no ?? ''),
                'esi_no' => $stat->esi_no ?? ($emp->esi_no ?? ''),
                'wwf_no' => $emp->wwf_no ?? '',
                'name' => $emp->full_name,
                'doj' => $emp->join_date ? \Carbon\Carbon::parse($emp->join_date)->format('d-m-Y') : '',

                'min_basic_da' => $minBasicDA,
                'min_hra' => $minHRA,
                'min_total' => $minBasicDA + $minHRA,

                'actual_basic_da' => $actualBasicDA,
                'actual_hra' => $actualHRA,
                'actual_total' => $actualBasicDA + $actualHRA,

                'payable_days' => $entry->attendance_days,
                'ot_holiday_others' => 0,
                'incentive' => 0,
                'gross_payable' => $part1Gross,
                'leave_encashment' => 0,
                'grand_total' => $part1Gross,

                'pf_salary' => $pfWage,
                'esi_salary' => $esiWage,

                'pf' => $pf,
                'esi' => $esi,
                'wwf' => $wwf,
                'pt' => $pt,
                'loan' => $loan,
                'advance' => $advance,
                'tds' => $tds,
                'other_deductions' => $otherDed,
                'total_deductions' => $totalDed,

                'net_salary' => $part1Net,
                'rounded_value' => round($part1Net),
                'payment_date' => $entry->batch->approved_at ? \Carbon\Carbon::parse($entry->batch->approved_at)->format('d-m-Y') : ''
            ];
        });
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $salaryType = $request->input('salary_type', 'all');
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with(['employee.department', 'employee.salaryAssignments.structure', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)
                  ->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        // Remove exclusion filter as all staffs should show in registers
        
        $entries = $query->get();
        $registerData = $this->formatRegisterData($entries, $salaryType);

        return Excel::download(new SalaryRegisterExport($registerData), 'salary_register.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $salaryType = $request->input('salary_type', 'all');
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with(['employee.department', 'employee.salaryAssignments.structure', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)
                  ->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $registerData = $this->formatRegisterData($entries, $salaryType);
        
        //$companyName = \App\Models\Setting::get('company_name', 'E-Office');
        //$companyLogo = public_path(\App\Models\Setting::get('company_logo', 'assets/img/icons/logo-dark.png'));
        $companyName = '';
        $companyLogo = public_path('assets/img/icons/logo-dark.png');
        
        $monthName = date("F", mktime(0, 0, 0, $month, 10));

        $pdf = Pdf::loadView('payroll.reports.salary_register_pdf', compact(
            'month', 'year', 'salaryType', 'structureId', 'registerData', 'companyName', 'companyLogo', 'monthName'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream("Salary_Register_{$monthName}_{$year}.pdf");
    }

    public function salaryPayFileIndex(Request $request)
    {
        $structures = SalaryStructure::all();
        $years = PayrollBatch::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');
        $salaryType = $request->input('salary_type', 'both');
        $generatedDate = $request->input('generated_date', date('d-m-Y'));

        $query = PayrollEntry::with([
            'employee.department', 
            'employee.salaryAssignments.structure', 
            'employee.statutoryDetails',
            'components', 
            'batch'
        ])->whereHas('batch', function($q) use ($month, $year) {
            $q->where('month', $month)
              ->where('year', $year);
        });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $payFileData = $this->formatPayFileData($entries, $salaryType);
        $companyBank = \App\Models\CompanyBankConfiguration::first();

        if ($request->input('export') === 'excel') {
            return Excel::download(new SalaryPayFileExport($payFileData, $companyBank, $month, $year, $generatedDate), 'Salary_Pay_File_Report.xlsx');
        }

        return view('payroll.reports.salary_pay_file', compact(
            'structures', 'years', 'month', 'year', 'structureId', 'payFileData', 'companyBank', 'salaryType', 'generatedDate'
        ));
    }

    private function formatPayFileData($entries, $salaryType = 'both')
    {
        return $entries->map(function($entry) use ($salaryType) {
            $emp = $entry->employee;
            $firstName = explode(' ', $emp->full_name)[0];
            $month = $entry->batch->month;
            $year = $entry->batch->year;
            $customerRefNo = $firstName . $emp->employeeID . sprintf('%02d', $month) . '.' . $year;

            $part1Net = $entry->net_salary;
            $part2Net = 0;

            $part1Net = $entry->net_salary;
            $part2Net = 0;

            $part1Gross = 0;
            $part1Ded = 0;
            $part2Gross = 0;
            $part2Ded = 0;

            foreach ($entry->components as $comp) {
                $amt = floatval($comp->amount);
                $name = strtolower($comp->component_name);
                
                // Force logic for parts if not explicitly set or to ensure consistency
                // Part 1 = Basic, DA, HRA, and ALL deductions
                // Part 2 = Everything else (Incentive, OT, Bonus, etc.)
                $part = $comp->part_number ?? 1;
                
                // Override part for known non-statutory earnings
                if ($comp->type == 'earning') {
                    $isStatutory = str_contains($name, 'basic') || $name == 'da' || str_contains($name, 'hra') || str_contains($name, 'rent');
                    if (!$isStatutory) {
                        $part = 2; // Extra earnings move to Part 2
                    } else {
                        $part = 1; // Basic/HRA are Part 1
                    }
                } else {
                    $part = 1; // All deductions stay in Part 1 for net pay calculation
                }

                if ($comp->type == 'earning') {
                    if ($part == 1) $part1Gross += $amt;
                    else $part2Gross += $amt;
                } else {
                    if ($part == 1) $part1Ded += $amt;
                    else $part2Ded += $amt;
                }
            }
            $part1Net = $part1Gross - $part1Ded;
            $part2Net = $part2Gross - $part2Ded;

            $amount = $entry->net_salary;
            if ($salaryType === 'part_a') {
                $amount = $part1Net;
                $customerRefNo .= '.P1';
            } elseif ($salaryType === 'part_b') {
                $amount = $part2Net;
                $customerRefNo .= '.P2';
            }

            return [
                'transaction_type' => $emp->transaction_type,
                'full_name' => $emp->full_name,
                'employeeID' => $emp->employeeID,
                'account_number' => $emp->account_number,
                'ifsc' => $emp->ifsc,
                'personal_email' => $emp->personal_email,
                'phonenumber' => $emp->phonenumber,
                'net_salary' => $amount,
                'customer_ref_no' => $customerRefNo,
            ];
        })->filter(function($row) {
            return $row['net_salary'] > 0 || $row['net_salary'] < 0; // Show negative if any, but usually > 0
        })->values();
    }

    public function exportFormXiPdf(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with([
            'employee.department', 
            'employee.salaryAssignments.structure', 
            'employee.statutoryDetails',
            'components', 
            'batch'
        ])->whereHas('batch', function($q) use ($month, $year) {
            $q->where('month', $month)
              ->where('year', $year);
        });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $formXiData = $this->formatFormXiData($entries);
        
        $companyName = \App\Models\Setting::get('company_name', 'ATS');
        $branch = "Main Office";
        
        $startDate = "01-" . sprintf('%02d', $month) . "-" . $year;
        $endDate = \Carbon\Carbon::create($year, $month)->endOfMonth()->format('d-m-Y');
        
        $pdf = Pdf::loadView('payroll.reports.form_xi_pdf', compact(
            'month', 'year', 'structureId', 'formXiData', 'companyName', 'branch', 'startDate', 'endDate'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream("Form_XI_Register_of_Wages_{$month}_{$year}.pdf");
    }

    public function esiStatementIndex(Request $request)
    {
        $structures = SalaryStructure::all();
        $years = PayrollBatch::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with([
            'employee.department', 
            'employee.salaryAssignments.structure', 
            'components', 
            'batch'
        ])->whereHas('batch', function($q) use ($month, $year) {
            $q->where('month', $month)
              ->where('year', $year);
        });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $esiData = $this->formatESIStatementData($entries);
        $esiEmployerPercent = \App\Models\Setting::get('payroll_esi_employer_percent', 3.25);

        return view('payroll.reports.esi_statement', compact(
            'structures', 'years', 'month', 'year', 'structureId', 'esiData', 'esiEmployerPercent'
        ));
    }

    private function formatESIStatementData($entries)
    {
        $esiLimit = \App\Models\Setting::get('payroll_esi_wage_limit', 21000);
        $esiBaseConfig = \App\Models\Setting::get('payroll_esi_employee_base', 'Gross');

        return $entries->map(function ($entry, $index) use ($esiLimit, $esiBaseConfig) {
            $emp = $entry->employee;
            $components = $entry->components;

            // Employee ESI
            $esiEmployee = $components->filter(function($c) {
                return (stripos($c->component_name, 'ESI') !== false || stripos($c->component_name, 'Insurance') !== false) 
                       && $c->type === 'deduction';
            })->sum('amount');

            if ($esiEmployee == 0 && !$entry->is_part_wise) {
                 $esiEmployee = $entry->esi_amount ?? 0;
            }

            // Employer ESI
            $esiEmployer = $components->filter(function($c) {
                return (stripos($c->component_name, 'ESI') !== false || stripos($c->component_name, 'Insurance') !== false) 
                       && $c->type === 'employer_contribution';
            })->sum('amount');

            $esiWage = 0;
            if ($esiEmployee > 0 || $esiEmployer > 0) {
                if (strtolower($esiBaseConfig) === 'gross') {
                    $esiWage = $entry->is_part_wise ? $entry->part1_gross : $entry->gross_salary;
                } else {
                    $esiBaseComponents = array_map('trim', explode(',', strtolower($esiBaseConfig)));
                    $esiWage = $components->where('type', 'earning')
                        ->filter(function($comp) use ($esiBaseComponents) {
                            return in_array(strtolower($comp->component_name), $esiBaseComponents);
                        })->sum('amount');
                }
                $esiWage = round($esiWage);
                if ($esiWage > $esiLimit && $esiLimit > 0) $esiWage = $esiLimit;
            }

            // Fallback calculation for Employer ESI if missing from components
            if ($esiEmployer == 0 && $esiEmployee > 0) {
                $esiEmployerPercent = \App\Models\Setting::get('payroll_esi_employer_percent', 3.25);
                $esiEmployer = ceil($esiWage * ($esiEmployerPercent / 100));
            }

            return [
                'sl_no' => $index + 1,
                'emp_code' => $emp->employeeID,
                'name' => $emp->full_name,
                'esi_salary' => $esiWage,
                'employee_contri' => $esiEmployee,
                'employer_contri' => $esiEmployer,
            ];
        })->filter(function($row) {
            return $row['employee_contri'] > 0 || $row['employer_contri'] > 0;
        })->values();
    }

    public function exportESIStatementExcel(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with(['employee', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)
                  ->where('year', $year);
            });

        if ($structureId) {
             $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $esiData = $this->formatESIStatementData($entries);
        $esiEmployerPercent = \App\Models\Setting::get('payroll_esi_employer_percent', 3.25);

        return Excel::download(new \App\Exports\ESIStatementExport($esiData, $month, $year, $esiEmployerPercent), 'ESI_Statement_Report.xlsx');
    }

    public function exportESIStatementPdf(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = PayrollEntry::with(['employee', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)
                  ->where('year', $year);
            });

        if ($structureId) {
             $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)
                  ->where('status', true);
            });
        }

        $entries = $query->get();
        $esiData = $this->formatESIStatementData($entries);
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $companyName = \App\Models\Setting::get('company_name', 'ATS');
        $esiEmployerPercent = \App\Models\Setting::get('payroll_esi_employer_percent', 3.25);

        $pdf = Pdf::loadView('payroll.reports.esi_statement_pdf', compact(
            'month', 'year', 'monthName', 'esiData', 'companyName', 'esiEmployerPercent'
        ));
        
        return $pdf->stream("ESI_Statement_{$monthName}_{$year}.pdf");
    }

    public function wwfStatementIndex(\Illuminate\Http\Request $request)
    {
        $structures = \App\Models\SalaryStructure::all();
        $years = \App\Models\PayrollBatch::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = \App\Models\PayrollEntry::with(['employee', 'components', 'batch'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)->where('status', true);
            });
        }

        $entries = $query->get();
        $wwfData = $this->formatWWFStatementData($entries);

        return view('payroll.reports.wwf_statement', compact(
            'structures', 'years', 'month', 'year', 'structureId', 'wwfData'
        ));
    }

    private function formatWWFStatementData($entries)
    {
        return $entries->map(function ($entry, $index) {
            $emp = $entry->employee;
            $components = $entry->components;

            $wwfEmployee = $components
                ->where('component_name', 'WWF')
                ->where('type', 'deduction')
                ->sum('amount');

            $wwfEmployer = $components
                ->filter(function($c) {
                    return stripos($c->component_name, 'WWF') !== false
                        && $c->type === 'employer_contribution';
                })->sum('amount');

            return [
                'sl_no'           => $index + 1,
                'emp_code'        => $emp->employeeID,
                'name'            => $emp->full_name,
                'employee_contri' => $wwfEmployee,
                'employer_contri' => $wwfEmployer,
            ];
        })->filter(function($row) {
            return $row['employee_contri'] > 0 || $row['employer_contri'] > 0;
        })->values();
    }

    public function exportWWFStatementExcel(\Illuminate\Http\Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = \App\Models\PayrollEntry::with(['employee', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)->where('status', true);
            });
        }

        $entries = $query->get();
        $wwfData = $this->formatWWFStatementData($entries);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\WWFStatementExport($wwfData, $month, $year), 'WWF_Statement_Report.xlsx');
    }

    public function exportWWFStatementPdf(\Illuminate\Http\Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = \App\Models\PayrollEntry::with(['employee', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)->where('status', true);
            });
        }

        $entries = $query->get();
        $wwfData = $this->formatWWFStatementData($entries);
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $companyName = \App\Models\Setting::get('company_name', 'ATS');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.reports.wwf_statement_pdf', compact(
            'month', 'year', 'monthName', 'wwfData', 'companyName'
        ));

        return $pdf->stream("WWF_Statement_{$monthName}_{$year}.pdf");
    }

    public function pfStatementIndex(\Illuminate\Http\Request $request)
    {
        $structures = \App\Models\SalaryStructure::all();
        $years = \App\Models\PayrollBatch::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = \App\Models\PayrollEntry::with(['employee', 'components', 'batch'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)->where('status', true);
            });
        }

        $entries = $query->get();
        $pfData = $this->formatPFStatementData($entries);

        return view('payroll.reports.pf_statement', compact(
            'structures', 'years', 'month', 'year', 'structureId', 'pfData'
        ));
    }

    private function formatPFStatementData($entries)
    {
        $pfLimit = \App\Models\Setting::get('payroll_pf_wage_limit', 15000);
        $pfBaseConfig = \App\Models\Setting::get('payroll_pf_employee_base', 'Basic');

        return $entries->map(function ($entry, $index) use ($pfLimit, $pfBaseConfig) {
            $emp = $entry->employee;
            $components = $entry->components;

            // Employee PF
            $pfEmployee = $components->filter(function($c) {
                return (stripos($c->component_name, 'PF') !== false || stripos($c->component_name, 'Provident Fund') !== false) 
                       && $c->type === 'deduction';
            })->sum('amount');

            if ($pfEmployee == 0 && !$entry->is_part_wise) {
                 $pfEmployee = $entry->pf_amount ?? 0;
            }

            // Employer Contribution (usually 12%)
            $pfEmployer = $components->filter(function($c) {
                return (stripos($c->component_name, 'PF') !== false || stripos($c->component_name, 'Provident Fund') !== false) 
                       && $c->type === 'employer_contribution';
            })->sum('amount');

            $pfWage = 0;
            if ($pfEmployee > 0 || $pfEmployer > 0) {
                if (strtolower($pfBaseConfig) === 'gross') {
                    $pfWage = $entry->is_part_wise ? $entry->part1_gross : $entry->gross_salary;
                } else {
                    $pfBaseComponents = array_map('trim', explode(',', strtolower($pfBaseConfig)));
                    $pfWage = $components->where('type', 'earning')
                        ->filter(function($comp) use ($pfBaseComponents) {
                            return in_array(strtolower($comp->component_name), $pfBaseComponents);
                        })->sum('amount');
                }
                $pfWage = round($pfWage);
                // PF Wage for calculation of components might be capped at 15000 for standard companies
                // but we report the actual statutory wage base
            }

            // EPS (8.33% of wage base, capped at pfLimit)
            $epsWage = ($pfLimit > 0 && $pfWage > $pfLimit) ? $pfLimit : $pfWage;
            
            // Fallback calculation for Employer Contribution if missing from components
            if ($pfEmployer == 0 && $pfEmployee > 0) {
                $pfEmployerPercent = \App\Models\Setting::get('payroll_pf_employer_percent', 12);
                $pfEmployer = round($epsWage * ($pfEmployerPercent / 100));
            }

            $eps = round($epsWage * 0.0833);
            
            // EPF (Remaining of Employer Contribution)
            $epf = $pfEmployer - $eps;
            if ($epf < 0) {
                $eps = $pfEmployer;
                $epf = 0;
            }

            return [
                'sl_no' => $index + 1,
                'emp_code' => $emp->employeeID,
                'name' => $emp->full_name,
                'pf_salary' => $pfWage,
                'employee_contri' => $pfEmployee,
                'employer_contri' => $pfEmployer,
                'eps' => $eps,
                'epf' => $epf,
            ];
        })->filter(function($row) {
            return $row['employee_contri'] > 0 || $row['employer_contri'] > 0;
        })->values();
    }

    public function exportPFStatementExcel(\Illuminate\Http\Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = \App\Models\PayrollEntry::with(['employee', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)->where('status', true);
            });
        }

        $entries = $query->get();
        $pfData = $this->formatPFStatementData($entries);

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PFStatementExport($pfData, $month, $year), 'PF_Statement_Report.xlsx');
    }

    public function exportPFStatementPdf(\Illuminate\Http\Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $structureId = $request->input('structure_id');

        $query = \App\Models\PayrollEntry::with(['employee', 'components'])
            ->whereHas('batch', function($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            });

        if ($structureId) {
            $query->whereHas('employee.salaryAssignments', function($q) use ($structureId) {
                $q->where('salary_structure_id', $structureId)->where('status', true);
            });
        }

        $entries = $query->get();
        $pfData = $this->formatPFStatementData($entries);
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $companyName = \App\Models\Setting::get('company_name', 'ATS');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.reports.pf_statement_pdf', compact(
            'month', 'year', 'monthName', 'pfData', 'companyName'
        ));

        return $pdf->stream("PF_Statement_{$monthName}_{$year}.pdf");
    }
    }
