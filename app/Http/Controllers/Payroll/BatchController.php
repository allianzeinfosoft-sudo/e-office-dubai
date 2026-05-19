<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollBatch;
use App\Models\PayrollEntry;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\Department;
use App\Models\Setting;
use App\Services\Payroll\PayrollService;
use App\Services\Payroll\PayslipGenerator;
use App\Services\Payroll\SalaryCalculator;
use App\Services\Payroll\StatutoryCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    protected $payrollService;
    protected $payslipGenerator;
    protected $calculator;
    protected $statutory;

    public function __construct(PayrollService $payrollService, PayslipGenerator $payslipGenerator, SalaryCalculator $calculator, StatutoryCalculator $statutory)
    {
        $this->payrollService = $payrollService;
        $this->payslipGenerator = $payslipGenerator;
        $this->calculator = $calculator;
        $this->statutory = $statutory;
    }

    public function index()
    {
        $batches = PayrollBatch::with('department', 'structure', 'processor')->latest()->paginate(10);
        return view('payroll.batches.index', compact('batches'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'department_id' => 'nullable|exists:departments,id',
            'salary_structure_id' => 'nullable|exists:salary_structures,id',
            'is_part_wise' => 'nullable|boolean'
        ]);

        $existing = \App\Models\PayrollBatch::where('month', $request->month)
            ->where('year', $request->year);

        if ($request->department_id) {
            $existing->where(function ($q) use ($request) {
                $q->whereNull('department_id')
                    ->orWhere('department_id', $request->department_id);
            });
        }
        
        if ($request->salary_structure_id) {
             $existing->where('salary_structure_id', $request->salary_structure_id);
        }

        if ($existing->exists()) {
            return back()->with('error', 'A payroll batch already exists that overlaps with the selected period and department.');
        }

        $batch = $this->payrollService->generateBatch(
            $request->month,
            $request->year,
            $request->department_id,
            $request->boolean('is_part_wise')
        );

        return redirect()->route('payroll.batches.show', $batch->id)
            ->with('success', 'Payroll batch generated successfully.');
    }

    public function create()
    {
        $departments = Department::all();
        $structures = SalaryStructure::where('status', 1)->get();
        return view('payroll.batches.create', compact('departments', 'structures'));
    }

    public function setupManual(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'department_id' => 'nullable|exists:departments,id',
            'salary_structure_ids' => 'nullable|array',
            'salary_structure_ids.*' => 'exists:salary_structures,id',
            'is_part_wise' => 'nullable|boolean'
        ]);

        $month = $request->month;
        $year = $request->year;
        $department_id = $request->department_id;
        $salary_structure_ids = $request->salary_structure_ids;
        $is_part_wise = $request->boolean('is_part_wise');

        // Determine which structures to process
        $structuresQuery = SalaryStructure::where('status', 1);
        if (!empty($salary_structure_ids)) {
            $structuresQuery->whereIn('id', $salary_structure_ids);
        }

        $allStructures = $structuresQuery->get();

        // Check for existing batches for each structure
        foreach ($allStructures as $structure) {
            $existing = \App\Models\PayrollBatch::where('month', $month)
                ->where('year', $year)
                ->where('salary_structure_id', $structure->id);

            if ($department_id) {
                $existing->where(function ($q) use ($department_id) {
                    $q->whereNull('department_id')
                        ->orWhere('department_id', $department_id);
                });
            }

            if ($existing->exists()) {
                return back()->with('error', "A payroll batch already exists for '{$structure->name}' in the selected period.");
            }
        }

        $batchDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        $structures = $allStructures->load([
            'components',
            'assignments' => function ($q) use ($department_id, $batchDate) {
                $q->where('effective_date', '<=', $batchDate)
                  ->whereIn('id', function($sub) use ($batchDate) {
                      $sub->select(\DB::raw('MAX(id)'))
                          ->from('employee_salary_assignments')
                          ->where('effective_date', '<=', $batchDate)
                          ->groupBy('employee_id');
                  })
                  ->whereHas('employee', function ($eq) use ($department_id) {
                      if ($department_id) {
                          $eq->where('department_id', $department_id);
                      }
                  })->with(['employee', 'components']);
            }
        ]);

        $structures = $structures->filter(function ($structure) {
            return $structure->assignments->count() > 0;
        });

        if ($structures->isEmpty()) {
            return back()->with('error', 'No active salary structures found with assigned active employees.');
        }

        $payrollSettings = [
            'pf_employee_percent' => Setting::get('payroll_pf_employee_percent', 12),
            'pf_employer_percent' => Setting::get('payroll_pf_employer_percent', 12),
            'pf_wage_limit' => Setting::get('payroll_pf_wage_limit', 15000),
            'pf_employee_base' => Setting::get('payroll_pf_employee_base', 'Basic Salary'),
            'pf_employer_base' => Setting::get('payroll_pf_employer_base', 'Basic Salary, DA'),
            'esi_employee_percent' => Setting::get('payroll_esi_employee_percent', 0.75),
            'esi_employer_percent' => Setting::get('payroll_esi_employer_percent', 3.25),
            'esi_wage_limit' => Setting::get('payroll_esi_wage_limit', 21000),
            'esi_employee_base' => Setting::get('payroll_esi_employee_base', 'Gross'),
            'esi_employer_base' => Setting::get('payroll_esi_employer_base', 'Gross'),
            'pf_employer_use_fixed' => Setting::get('payroll_pf_employer_use_fixed', 1),
            'esi_employer_use_fixed' => Setting::get('payroll_esi_employer_use_fixed', 0),
        ];

        // Prepare data for Handsontable per structure
        $hotData = [];

        foreach ($structures as $structure) {
            $hotData[$structure->id] = [];
            foreach ($structure->assignments as $assignment) {
                $employee = $assignment->employee;
                
                $lopDaysFromAttendance = $this->calculator->calculateLOPDays($employee, $month, $year);
                $payable = 30 - $lopDaysFromAttendance;
                $lopDays = 0; // Manual deduction starts at 0
                
                $basicDaComp = $assignment->components->filter(function($c) {
                    $name = strtolower($c->name);
                    return str_contains($name, 'basic') || $name === 'da' || str_contains($name, 'basic+da');
                })->first();
                $basicDaRate = $basicDaComp ? $basicDaComp->pivot->amount : $assignment->base_amount;
                
                $hraComp = $assignment->components->filter(function($c) {
                    $name = strtolower($c->name);
                    return $name === 'hra' || str_contains($name, 'house rent');
                })->first();
                $hraRate = $hraComp ? $hraComp->pivot->amount : 0;

                $row = [
                    'id' => $employee->id,
                    'code' => $employee->employeeID,
                    'name' => $employee->full_name,
                    'ctc_basic_da' => round($basicDaRate, 2),
                    'ctc_hra' => round($hraRate, 2),
                    'ctc_total' => round($basicDaRate + $hraRate, 2),
                    'payable_days' => $payable,
                    'deduction_days' => $lopDays,
                    'ot_days' => 0,
                    'pf_salary' => 0,
                    'esi_salary' => 0,
                    'pf' => 0,
                    'esi' => 0,
                    'incentive' => 0,
                    'ot_add' => 0,
                    'remarks' => '',
                    'pf_eligible' => $assignment->pf_eligible ? 1 : 0,
                    'esi_eligible' => $assignment->esi_eligible ? 1 : 0,
                    'pf_no' => $employee->pf_no,
                    'esi_no' => $employee->esi_no,
                    'wwf' => 0,
                    'salary_structure_id' => $structure->id,
                ];

                $calcResult = $this->calculator->calculate($employee, $month, $year, $is_part_wise);

                // Add all calculated components to the row data
                if ($calcResult && isset($calcResult['components'])) {
                    foreach ($calcResult['components'] as $comp) {
                        $compName = $comp['name'];
                        $lowercaseName = strtolower($compName);
                        $val = $comp['amount'];
                        
                        // Check if this specific component is marked as editable from the structure
                        $structComp = $structure->components->where('name', $compName)->first();
                        $isEditable = $structComp ? ($structComp->pivot->is_editable ? 1 : 0) : 0;

                        if (str_contains($lowercaseName, 'basic') || $lowercaseName === 'da' || str_contains($lowercaseName, 'basic+da')) {
                            $row['earned_basic_da_editable'] = $isEditable;
                        } elseif (str_contains($lowercaseName, 'hra') || str_contains($lowercaseName, 'house rent')) {
                            $row['earned_hra_editable'] = $isEditable;
                        } elseif (str_contains($lowercaseName, 'incentive')) {
                            $row['incentive'] = $val;
                            $row['ctc_incentive'] = $comp['standard_amount'];
                            $row['incentive_editable'] = $isEditable;
                            $row['incentive_is_attendance_based'] = $comp['is_attendance_based'];
                        } elseif (str_contains($lowercaseName, 'ot') || str_contains($lowercaseName, 'overtime')) {
                            $row['ot_add'] = $val;
                            $row['ctc_ot_add'] = $comp['standard_amount'];
                            $row['ot_add_editable'] = $isEditable;
                            $row['ot_add_is_attendance_based'] = $comp['is_attendance_based'];
                        } elseif ($comp['type'] === 'employer_contribution') {
                            // Employer contributions like WWF Employer should not show in manual entry deductions
                            continue;
                        } elseif (str_contains($lowercaseName, 'pf') || str_contains($lowercaseName, 'esi')) {
                            // Already handled by standard PF/ESI logic, skip adding as custom component
                            continue;
                        } elseif ($lowercaseName === 'wwf') {
                            $row['wwf'] = $val;
                            $row['wwf_editable'] = $isEditable;
                            continue;
                        } else {
                            // Custom components
                            $compKey = 'comp_' . str_replace(' ', '_', $lowercaseName);
                            $row[$compKey] = $val;
                            $row[$compKey . '_standard'] = $comp['standard_amount'];
                            $row[$compKey . '_editable'] = $isEditable;
                            $row[$compKey . '_name'] = $compName;
                            $row[$compKey . '_type'] = $comp['type'];
                            $row[$compKey . '_is_attendance_based'] = $comp['is_attendance_based'];
                        }
                    }
                }

                $hotData[$structure->id][] = $row;
            }
        }

        return view('payroll.batches.setup_manual', compact('structures', 'month', 'year', 'department_id', 'is_part_wise', 'payrollSettings', 'hotData'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'department_id' => 'nullable|exists:departments,id',
            'is_part_wise' => 'nullable|boolean',
            'entries_json' => 'required|string',
        ]);

        $entries = json_decode($request->entries_json, true) ?? [];

        if (empty($entries)) {
            return back()->with('error', 'No payroll entries provided.');
        }

        // Group entries by salary_structure_id
        $groupedEntries = collect($entries)->groupBy('salary_structure_id');

        return DB::transaction(function () use ($request, $groupedEntries) {
            foreach ($groupedEntries as $structureId => $structureEntries) {
                $structure = SalaryStructure::with('components')->find($structureId);
                $batch = PayrollBatch::create([
                    'month' => $request->month,
                    'year' => $request->year,
                    'department_id' => $request->department_id,
                    'salary_structure_id' => $structureId,
                    'is_part_wise' => $request->boolean('is_part_wise'),
                    'status' => 'draft',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);

                foreach ($structureEntries as $data) {
                    $employee_id = $data['id'];
                    
                    $componentsData = [
                        "Basic+DA" => ["amount" => round($data["earned_basic_da"] ?? 0), "standard" => $data["ctc_basic_da"], "type" => "earning", "part" => 1, "is_stat" => true],
                        "HRA" => ["amount" => round($data["earned_hra"] ?? 0), "standard" => $data["ctc_hra"], "type" => "earning", "part" => 1, "is_stat" => true],
                        'Incentive' => ['amount' => $data['incentive'] ?? 0, 'standard' => $data['incentive'], 'type' => 'earning', 'part' => 2, 'is_stat' => false],
                        'OT' => ['amount' => $data['ot_add'] ?? 0, 'standard' => $data['ot_add'], 'type' => 'earning', 'part' => 2, 'is_stat' => false],
                        'PF' => ['amount' => $data['pf'] ?? 0, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => true],
                        'ESI' => ['amount' => $data['esi'] ?? 0, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => true],
                        'WWF' => ['amount' => $data['wwf'] ?? 0, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => false],
                    ];

                    foreach ($data as $key => $val) {
                        if (str_starts_with($key, 'comp_') && !str_ends_with($key, '_editable') && !str_ends_with($key, '_name') && !str_ends_with($key, '_type') && !str_ends_with($key, '_standard') && !str_ends_with($key, '_is_attendance_based')) {
                            $compName = $data[$key . '_name'] ?? str_replace('comp_', '', $key);
                            $componentsData[$compName] = [
                                'amount' => $val,
                                'standard' => $val, 
                                'type' => $data[$key . '_type'] ?? 'earning',
                                'part' => ($data[$key . '_type'] ?? 'earning') == 'earning' ? 2 : 1, 
                                'is_stat' => false
                            ];
                        }
                    }

                    // Calculate ESI split for Part 1 / Part 2 consistency (Matching Frontend Logic)
                    $totalEsi = floatval($data['esi'] ?? 0);
                    $esiRate = \App\Models\Setting::get('payroll_esi_employee_percent', 0.75) / 100;
                    $esiWageLimit = \App\Models\Setting::get('payroll_esi_wage_limit', 21000);
                    $p1GrossVal = floatval($data['earned_basic_da'] ?? 0) + floatval($data['earned_hra'] ?? 0);
                    
                    $p1EsiSalary = ($data['esi_eligible'] ?? 0) ? min($p1GrossVal, $esiWageLimit) : 0;
                    $p1Esi = min($totalEsi, ($data["esi_eligible"] ?? 0) ? round($p1EsiSalary * $esiRate) : 0);
                    $p2Esi = max(0, $totalEsi - $p1Esi);

                    // Update componentsData map for ESI to store correctly in database
                    unset($componentsData['ESI']);
                    $componentsData['ESI'] = ['amount' => $p1Esi, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => true];
                    if ($p2Esi > 0) {
                        $componentsData['ESI Part 2'] = ['amount' => $p2Esi, 'standard' => 0, 'type' => 'deduction', 'part' => 2, 'is_stat' => true, 'component_name' => 'ESI'];
                    }

                    $totalGross = 0;
                    $totalDed = 0;
                    $part1Gross = 0;
                    $part1Ded = 0;
                    $part2Gross = 0;
                    $part2Ded = 0;

                    foreach ($componentsData as $name => $comp) {
                        $amt = floatval($comp['amount']);
                        if ($comp['type'] == 'earning') {
                            $totalGross += $amt;
                            if ($comp['part'] == 1) $part1Gross += $amt;
                            else $part2Gross += $amt;
                        } elseif ($comp['type'] == 'deduction') {
                            $totalDed += $amt;
                            if ($comp['part'] == 1) $part1Ded += $amt;
                            else $part2Ded += $amt;
                        }
                    }

                    // Calculate Employer Contributions automatically based on the final data
                    $calcComponentsFormat = [];
                    foreach ($componentsData as $name => $comp) {
                        $calcComponentsFormat[] = [
                            'name' => $name,
                            'type' => $comp['type'],
                            'standard_amount' => $comp['standard'],
                            'amount' => $comp['amount'],
                            'part_number' => $comp['part'],
                            'is_ctc_variable' => (str_contains(strtolower($name), 'basic') || $name === 'HRA' || $name === 'DA')
                        ];
                    }

                    $employee = Employee::find($employee_id);
                    $assignment = $employee->currentSalaryAssignment;
                    $totalEmployerContribution = 0;
                    
                    if ($structure) {
                        foreach ($structure->components->where('type', 'employer_contribution') as $erComp) {
                            $erAmt = 0;
                            $erStd = 0;
                            $lowErName = strtolower($erComp->name);
                            $erCalcType = $erComp->pivot->calculation_type;
                            $erVal = $assignment->components->where('id', $erComp->id)->first()?->pivot->amount ?? $erComp->pivot->value;

                            if ($erComp->is_statutory && (str_contains($lowErName, 'pf') || str_contains($lowErName, 'esi'))) {
                                if (str_contains($lowErName, 'pf') && $assignment->pf_eligible) {
                                    $erAmt = $this->statutory->calculatePF($data['earned_basic_da'] ?? 0, $calcComponentsFormat, $totalGross, true, $data['ctc_basic_da'] ?? 0);
                                    $erStd = $erAmt; // Simplified standard for PF
                                } elseif (str_contains($lowErName, 'esi') && $assignment->esi_eligible) {
                                    $erAmt = $this->statutory->calculateESI($totalGross, $calcComponentsFormat, true);
                                    $erStd = $erAmt;
                                }
                            } else {
                                // Default logic for employer parts like WWF
                                if ($erCalcType == 'fixed') {
                                    $erStd = $erVal;
                                    $erAmt = $erVal;
                                } elseif ($erCalcType == 'percentage') {
                                    $erStd = round(($data['ctc_basic_da'] + $data['ctc_hra']) * ($erVal / 100), 2);
                                    $erAmt = $erStd;
                                } elseif ($erCalcType == 'earned_percentage') {
                                    $erStd = round(($data['ctc_basic_da'] + $data['ctc_hra']) * ($erVal / 100), 2);
                                    $erAmt = round(($data['earned_basic_da'] + $data['earned_hra']) * ($erVal / 100), 2);
                                }

                                if ($erComp->is_attendance_based && $erCalcType != 'earned_percentage') {
                                    $workingDays = 30; // standard
                                    $payableDays = $data['payable_days'] ?? 30;
                                    $erAmt = round($erStd * ($payableDays / $workingDays), 2);
                                }
                            }

                            if ($erAmt > 0 || $erStd > 0) {
                                $componentsData[$erComp->name] = [
                                    'amount' => $erAmt, 
                                    'standard' => $erStd, 
                                    'type' => 'employer_contribution', 
                                    'part' => 1, 
                                    'is_stat' => $erComp->is_statutory
                                ];
                                $totalEmployerContribution += $erAmt;
                            }
                        }
                    }

                    $entry = $batch->entries()->create([
                        'employee_id' => $employee_id,
                        'salary_structure_id' => $data['salary_structure_id'] ?? $structureId,
                        'gross_salary' => $totalGross,
                        'total_deductions' => $totalDed,
                        'total_employer_contribution' => $totalEmployerContribution,
                        'net_salary' => $totalGross - $totalDed,
                        'ctc' => ($data['ctc_total'] ?? 0) + $totalEmployerContribution,
                        'lop_days' => $data['deduction_days'] ?? 0,
                        'attendance_days' => $data['payable_days'] ?? 0,
                        'ot_amount' => $data['ot_add'] ?? 0,
                        'part1_gross' => $part1Gross,
                        'part1_deductions' => $part1Ded,
                        'part1_net' => $part1Gross - $part1Ded,
                        'part2_gross' => $part2Gross,
                        'part2_deductions' => $part2Ded,
                        'part2_net' => $part2Gross - $part2Ded,
                        'remarks' => $data['remarks'] ?? null,
                    ]);

                    foreach ($componentsData as $name => $comp) {
                        if (floatval($comp['amount']) == 0 && !in_array($name, ['PF', 'ESI', 'WWF'])) continue;
                        $entry->components()->create([
                            'component_name' => $comp['component_name'] ?? $name,
                            'type' => $comp['type'],
                            'standard_amount' => $comp['standard'] ?? $comp['amount'],
                            'amount' => $comp['amount'],
                            'part_number' => $comp['part'],
                            'is_ctc_variable' => false,
                            'is_attendance_based' => $comp['is_stat'],
                        ]);
                    }
                }
            }
            return redirect()->route('payroll.batches.index')
                ->with('success', count($groupedEntries) . ' Payroll batches created successfully.');
        });
    }

    public function show(PayrollBatch $batch)
    {
        $batch->load('entries.employee.currentSalaryAssignment', 'department');
        $employeeIds = $batch->entries->pluck('employee_id');
        $structureIds = $batch->entries->pluck('salary_structure_id')->unique()->filter();
        $structures = SalaryStructure::whereIn('id', $structureIds)->get();
        return view('payroll.batches.show', compact('batch', 'structures'));
    }

    public function edit(PayrollBatch $batch)
    {
        if ($batch->status === 'paid' || $batch->status === 'approved') {
            return back()->with('error', 'Only draft batches can be edited.');
        }
        $batch->load(['entries.components', 'entries.employee']);
        $month = $batch->month;
        $year = $batch->year;
        $department_id = $batch->department_id;

        $employeeIds = $batch->entries->pluck('employee_id')->toArray();

        $structures = SalaryStructure::whereHas('assignments', function ($q) use ($employeeIds) {
            $q->whereIn('employee_id', $employeeIds);
        })->with([
            'components',
            'assignments' => function ($q) use ($employeeIds) {
                $q->whereIn('employee_id', $employeeIds)->with('employee');
            }
        ])->get();

        $payrollSettings = [
            'pf_employee_percent' => Setting::get('payroll_pf_employee_percent', 12),
            'pf_employer_percent' => Setting::get('payroll_pf_employer_percent', 12),
            'pf_wage_limit' => Setting::get('payroll_pf_wage_limit', 15000),
            'pf_employee_base' => Setting::get('payroll_pf_employee_base', 'Basic Salary'),
            'pf_employer_base' => Setting::get('payroll_pf_employer_base', 'Basic Salary, DA'),
            'esi_employee_percent' => Setting::get('payroll_esi_employee_percent', 0.75),
            'esi_employer_percent' => Setting::get('payroll_esi_employer_percent', 3.25),
            'esi_wage_limit' => Setting::get('payroll_esi_wage_limit', 21000),
            'esi_employee_base' => Setting::get('payroll_esi_employee_base', 'Gross'),
            'esi_employer_base' => Setting::get('payroll_esi_employer_base', 'Gross'),
            'pf_employer_use_fixed' => Setting::get('payroll_pf_employer_use_fixed', 1),
            'esi_employer_use_fixed' => Setting::get('payroll_esi_employer_use_fixed', 0),
        ];

        $hotData = [];
        foreach ($structures as $structure) {
            $hotData[$structure->id] = [];
            $assignments = $structure->assignments->filter(function($a) use ($batch) {
                return $batch->entries->pluck('employee_id')->contains($a->employee_id);
            });

            foreach ($assignments as $assignment) {
                $employee = $assignment->employee;
                $entry = $batch->entries->where('employee_id', $employee->id)->first();
                
                $basicDaComp = $entry->components->filter(function($c) {
                    $name = strtolower($c->component_name);
                    return str_contains($name, 'basic') || $name === 'da' || str_contains($name, 'basic+da');
                })->first();
                
                $hraComp = $entry->components->filter(function($c) {
                    $name = strtolower($c->component_name);
                    return $name === 'hra' || str_contains($name, 'house rent');
                })->first();

                $row = [
                    'id' => $employee->id,
                    'code' => $employee->employeeID,
                    'name' => $employee->full_name,
                    'ctc_basic_da' => round($basicDaComp ? $basicDaComp->standard_amount : $assignment->base_amount, 2),
                    'ctc_hra' => round($hraComp ? $hraComp->standard_amount : 0, 2),
                    'ctc_total' => round($entry->ctc, 2),
                    'payable_days' => $entry->attendance_days,
                    'deduction_days' => $entry->lop_days,
                    'ot_days' => 0,
                    'pf_salary' => 0,
                    'esi_salary' => 0,
                    'pf' => $entry->components->where('component_name', 'PF')->sum('amount'),
                    'esi' => $entry->components->where('component_name', 'ESI')->sum('amount'),
                    'incentive' => $entry->components->where('component_name', 'Incentive')->sum('amount'),
                    'ctc_incentive' => $entry->components->where('component_name', 'Incentive')->first()?->standard_amount ?? 0,
                    'incentive_is_attendance_based' => $entry->components->where('component_name', 'Incentive')->first()?->is_attendance_based ?? false,
                    'ot_add' => $entry->ot_amount,
                    'ctc_ot_add' => $entry->components->where('component_name', 'OT')->first()?->standard_amount ?? $entry->ot_amount,
                    'ot_add_is_attendance_based' => $entry->components->where('component_name', 'OT')->first()?->is_attendance_based ?? false,
                    'remarks' => $entry->remarks,
                    'pf_eligible' => $assignment->pf_eligible ? 1 : 0,
                    'esi_eligible' => $assignment->esi_eligible ? 1 : 0,
                    'pf_no' => $employee->pf_no,
                    'esi_no' => $employee->esi_no,
                    'wwf' => $entry->components->where('component_name', 'WWF')->sum('amount'),
                    'salary_structure_id' => $structure->id,
                ];

                // Add custom components
                foreach ($entry->components as $comp) {
                    $compName = $comp->component_name;
                    $lowercaseName = strtolower($compName);
                    
                    // Skip standard ones already handled
                    if (in_array($compName, ['Basic+DA', 'HRA', 'Incentive', 'OT', 'PF', 'ESI', 'WWF'])) continue;
                    if (str_contains($lowercaseName, 'basic') || $lowercaseName === 'da' || str_contains($lowercaseName, 'basic+da')) continue;
                    if (str_contains($lowercaseName, 'hra') || str_contains($lowercaseName, 'house rent')) continue;

                    if ($comp->type === 'employer_contribution') continue; // Employer contributions should not show in manual edit

                    $compKey = 'comp_' . str_replace(' ', '_', $lowercaseName);
                    $row[$compKey] = $comp->amount;
                    $row[$compKey . '_standard'] = $comp->standard_amount;
                    $row[$compKey . '_is_attendance_based'] = $comp->is_attendance_based;
                    $row[$compKey . '_name'] = $compName;
                    $row[$compKey . '_type'] = $comp->type;
                    
                    // Find if it's editable from assignment
                    $structComp = $structure->components->where('name', $compName)->first();
                    $row[$compKey . '_editable'] = $structComp ? ($structComp->pivot->is_editable ? 1 : 0) : 1;
                }
                
                // Also add editable flags for standard components from the structure
                $row['earned_basic_da_editable'] = $structure->components->where('name', 'Basic+DA')->first()?->pivot->is_editable ? 1 : 0;
                $row['earned_hra_editable'] = $structure->components->where('name', 'HRA')->first()?->pivot->is_editable ? 1 : 0;
                $row['incentive_editable'] = $structure->components->where('name', 'Incentive')->first()?->pivot->is_editable ? 1 : 0;
                $row['ot_add_editable'] = $structure->components->where('name', 'OT')->first()?->pivot->is_editable ? 1 : 0;
                $row['wwf_editable'] = $structure->components->where('name', 'WWF')->first()?->pivot->is_editable ? 1 : 0;

                $hotData[$structure->id][] = $row;
            }
        }

        return view('payroll.batches.edit', compact('batch', 'structures', 'month', 'year', 'department_id', 'payrollSettings', 'hotData'));
    }

    public function update(Request $request, PayrollBatch $batch)
    {
        $request->validate([
            'entries_json' => 'required|string',
        ]);

        $entries = json_decode($request->entries_json, true) ?? [];

        return DB::transaction(function () use ($request, $batch, $entries) {
            foreach ($entries as $data) {
                $entry = $batch->entries->where('id', $data['id'])->first();
                $employee_id = $entry ? $entry->employee_id : ($data['employee_id'] ?? null);

                if ($entry) {
                    $componentsData = [
                        'Basic+DA' => ['amount' => round($data['earned_basic_da'] ?? 0), 'standard' => $data['ctc_basic_da'], 'type' => 'earning', 'part' => 1, 'is_stat' => true],
                        'HRA' => ['amount' => round($data['earned_hra'] ?? 0), 'standard' => $data['ctc_hra'], 'type' => 'earning', 'part' => 1, 'is_stat' => true],
                        'Incentive' => ['amount' => $data['incentive'] ?? 0, 'standard' => $data['incentive'], 'type' => 'earning', 'part' => 2, 'is_stat' => false],
                        'OT' => ['amount' => $data['ot_add'] ?? 0, 'standard' => $data['ot_add'], 'type' => 'earning', 'part' => 2, 'is_stat' => false],
                        'PF' => ['amount' => $data['pf'] ?? 0, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => true],
                        'ESI' => ['amount' => $data['esi'] ?? 0, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => true],
                        'WWF' => ['amount' => $data['wwf'] ?? 0, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => false],
                    ];

                    foreach ($data as $key => $val) {
                        if (str_starts_with($key, 'comp_') && !str_ends_with($key, '_editable') && !str_ends_with($key, '_name') && !str_ends_with($key, '_type') && !str_ends_with($key, '_standard') && !str_ends_with($key, '_is_attendance_based')) {
                            $compName = $data[$key . '_name'] ?? str_replace('comp_', '', $key);
                            $componentsData[$compName] = [
                                'amount' => $val,
                                'standard' => $val, 
                                'type' => $data[$key . '_type'] ?? 'earning',
                                'part' => ($data[$key . '_type'] ?? 'earning') == 'earning' ? 2 : 1, 
                                'is_stat' => false
                            ];
                        }
                    }

                    // Calculate ESI split for Part 1 / Part 2 consistency (Matching Frontend Logic)
                    $totalEsi = floatval($data['esi'] ?? 0);
                    $esiRate = \App\Models\Setting::get('payroll_esi_employee_percent', 0.75) / 100;
                    $esiWageLimit = \App\Models\Setting::get('payroll_esi_wage_limit', 21000);
                    $p1GrossVal = floatval($data['earned_basic_da'] ?? 0) + floatval($data['earned_hra'] ?? 0);
                    
                    $p1EsiSalary = ($data['esi_eligible'] ?? 0) ? min($p1GrossVal, $esiWageLimit) : 0;
                    $p1Esi = min($totalEsi, ($data["esi_eligible"] ?? 0) ? round($p1EsiSalary * $esiRate) : 0);
                    $p2Esi = max(0, $totalEsi - $p1Esi);

                    // Update componentsData map for ESI to store correctly in database
                    unset($componentsData['ESI']);
                    $componentsData['ESI'] = ['amount' => $p1Esi, 'standard' => 0, 'type' => 'deduction', 'part' => 1, 'is_stat' => true];
                    if ($p2Esi > 0) {
                        $componentsData['ESI Part 2'] = ['amount' => $p2Esi, 'standard' => 0, 'type' => 'deduction', 'part' => 2, 'is_stat' => true, 'component_name' => 'ESI'];
                    }

                    $totalGross = 0;
                    $totalDed = 0;
                    $part1Gross = 0;
                    $part1Ded = 0;
                    $part2Gross = 0;
                    $part2Ded = 0;

                    foreach ($componentsData as $name => $comp) {
                        $amt = floatval($comp['amount']);
                        if ($comp['type'] == 'earning') {
                            $totalGross += $amt;
                            if ($comp['part'] == 1) $part1Gross += $amt;
                            else $part2Gross += $amt;
                        } elseif ($comp['type'] == 'deduction') {
                            $totalDed += $amt;
                            if ($comp['part'] == 1) $part1Ded += $amt;
                            else $part2Ded += $amt;
                        }
                    }

                    // Calculate Employer Contributions automatically based on the final data
                    $calcComponentsFormat = [];
                    foreach ($componentsData as $name => $comp) {
                        $calcComponentsFormat[] = [
                            'name' => $name,
                            'type' => $comp['type'],
                            'standard_amount' => $comp['standard'],
                            'amount' => $comp['amount'],
                            'part_number' => $comp['part'],
                            'is_ctc_variable' => (str_contains(strtolower($name), 'basic') || $name === 'HRA' || $name === 'DA')
                        ];
                    }

                    $employee = Employee::find($employee_id);
                    $assignment = $employee->currentSalaryAssignment;
                    $structure = $batch->structure; // Loaded via relationship
                    if (!$structure) $structure = SalaryStructure::with('components')->find($data['salary_structure_id'] ?? 0);

                    $totalEmployerContribution = 0;
                    if ($structure) {
                        foreach ($structure->components->where('type', 'employer_contribution') as $erComp) {
                            $erAmt = 0;
                            $erStd = 0;
                            $lowErName = strtolower($erComp->name);
                            $erCalcType = $erComp->pivot->calculation_type;
                            $erVal = $assignment->components->where('id', $erComp->id)->first()?->pivot->amount ?? $erComp->pivot->value;

                            if ($erComp->is_statutory && (str_contains($lowErName, 'pf') || str_contains($lowErName, 'esi'))) {
                                if (str_contains($lowErName, 'pf') && $assignment->pf_eligible) {
                                    $erAmt = $this->statutory->calculatePF($data['earned_basic_da'] ?? 0, $calcComponentsFormat, $totalGross, true, $data['ctc_basic_da'] ?? 0);
                                    $erStd = $erAmt;
                                } elseif (str_contains($lowErName, 'esi') && $assignment->esi_eligible) {
                                    $erAmt = $this->statutory->calculateESI($totalGross, $calcComponentsFormat, true);
                                    $erStd = $erAmt;
                                }
                            } else {
                                if ($erCalcType == 'fixed') {
                                    $erStd = $erVal;
                                    $erAmt = $erVal;
                                } elseif ($erCalcType == 'percentage') {
                                    $erStd = round(($data['ctc_basic_da'] + $data['ctc_hra']) * ($erVal / 100), 2);
                                    $erAmt = $erStd;
                                } elseif ($erCalcType == 'earned_percentage') {
                                    $erStd = round(($data['ctc_basic_da'] + $data['ctc_hra']) * ($erVal / 100), 2);
                                    $erAmt = round(($data['earned_basic_da'] + $data['earned_hra']) * ($erVal / 100), 2);
                                }

                                if ($erComp->is_attendance_based && $erCalcType != 'earned_percentage') {
                                    $workingDays = 30;
                                    $payableDays = $data['payable_days'] ?? 30;
                                    $erAmt = round($erStd * ($payableDays / $workingDays), 2);
                                }
                            }

                            if ($erAmt > 0 || $erStd > 0) {
                                $componentsData[$erComp->name] = [
                                    'amount' => $erAmt, 
                                    'standard' => $erStd, 
                                    'type' => 'employer_contribution', 
                                    'part' => 1, 
                                    'is_stat' => $erComp->is_statutory
                                ];
                                $totalEmployerContribution += $erAmt;
                            }
                        }
                    }

                    $entry->update([
                        'salary_structure_id' => $data['salary_structure_id'] ?? $entry->salary_structure_id,
                        'gross_salary' => $totalGross,
                        'total_deductions' => $totalDed,
                        'total_employer_contribution' => $totalEmployerContribution,
                        'net_salary' => $totalGross - $totalDed,
                        'ctc' => ($data['ctc_total'] ?? 0) + $totalEmployerContribution,
                        'lop_days' => $data['deduction_days'] ?? 0,
                        'attendance_days' => $data['payable_days'] ?? 0,
                        'ot_amount' => $data['ot_add'] ?? 0,
                        'part1_gross' => $part1Gross,
                        'part1_deductions' => $part1Ded,
                        'part1_net' => $part1Gross - $part1Ded,
                        'part2_gross' => $part2Gross,
                        'part2_deductions' => $part2Ded,
                        'part2_net' => $part2Gross - $part2Ded,
                        'remarks' => $data['remarks'] ?? null,
                    ]);

                    $entry->components()->delete();
                    foreach ($componentsData as $name => $comp) {
                        if (floatval($comp['amount']) == 0 && !in_array($name, ['PF', 'ESI', 'WWF'])) continue;
                        $entry->components()->create([
                            'component_name' => $comp['component_name'] ?? $name,
                            'type' => $comp['type'],
                            'standard_amount' => $comp['standard'] ?? $comp['amount'],
                            'amount' => $comp['amount'],
                            'part_number' => $comp['part'],
                            'is_ctc_variable' => false,
                            'is_attendance_based' => $comp['is_stat'],
                        ]);
                    }
                }
            }
            return redirect()->route('payroll.batches.show', $batch->id)
                ->with('success', 'Payroll batch updated successfully.');
        });
    }


    public function approve(PayrollBatch $batch)
    {
        if ($batch->status !== 'draft' && $batch->status !== 'reviewed') {
            return back()->with('error', 'Only draft or reviewed batches can be approved.');
        }

        $batch->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Payroll batch approved successfully.');
    }

    public function refresh(PayrollBatch $batch)
    {
        if ($batch->status === 'approved' || $batch->status === 'paid') {
            return back()->with('error', 'Cannot refresh an approved or paid batch.');
        }

        $this->payrollService->refreshBatch($batch);

        return back()->with('success', 'Payroll batch refreshed with latest assignment data successfully.');
    }

    public function destroy(PayrollBatch $batch)
    {
        if ($batch->status === 'paid') {
            return back()->with('error', 'Paid batches cannot be deleted.');
        }

        $batch->delete();

        return redirect()->route('payroll.batches.index')
            ->with('success', 'Payroll batch deleted successfully.');
    }

    public function downloadPayslip(PayrollEntry $entry)
    {
        $pdf = $this->payslipGenerator->generate($entry);
        $pdf->setPaper('A4', 'landscape'); // <-- Important
        return $pdf->stream('payslip_' . $entry->employee->employeeID . '.pdf');
    //return $pdf->download('payslip_' . $entry->employee->employeeID . '.pdf');
    }

}