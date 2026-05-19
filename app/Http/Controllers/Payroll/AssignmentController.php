<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\EmployeeSalaryAssignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::withCount(['assignments' => function($query) {
            $query->where('status', true);
        }])->with(['assignments' => function($query) {
            $query->where('status', true)->with('employee');
        }])->get();

        return view('payroll.assignments.index', compact('structures'));
    }

    public function create()
    {
        $employees = Employee::all();
        $structures = SalaryStructure::all();
        return view('payroll.assignments.create', compact('employees', 'structures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_structure_id' => 'required|exists:salary_structures,id',
            'base_amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'monthly_ctc' => 'nullable|numeric|min:0',
            'annual_ctc' => 'nullable|numeric|min:0',
            'pf_eligible' => 'nullable|boolean',
            'esi_eligible' => 'nullable|boolean',
            'components' => 'nullable|array',
        ]);

        $lastProcessed = $this->getLastProcessedDate($request->employee_id);
        $currentAssignment = EmployeeSalaryAssignment::where('employee_id', $request->employee_id)->where('status', true)->first();
        if ($lastProcessed && \Carbon\Carbon::parse($request->effective_date)->lte($lastProcessed) && (!$currentAssignment || $currentAssignment->salary_structure_id != $request->salary_structure_id)) {
                        $nextMonth = $lastProcessed->copy()->addMonth()->format('F Y');
            return back()->withInput()->with('error', 'Payrole alredy procced on ' . $lastProcessed->format('F ,Y') . ' for employee. you need to change effective date ' . $nextMonth . '.');

        }

        return \DB::transaction(function () use ($request) {
            // Deactivate old assignments for this employee
            EmployeeSalaryAssignment::where('employee_id', $request->employee_id)
                ->update(['status' => false]);

            $assignmentData = $request->only([
                'employee_id',
                'salary_structure_id',
                'base_amount',
                'effective_date',
                'monthly_ctc',
                'annual_ctc'
            ]);
            $assignmentData['pf_eligible'] = $request->has('pf_eligible') ? 1 : 0;
            $assignmentData['esi_eligible'] = $request->has('esi_eligible') ? 1 : 0;
            $assignmentData['status'] = true;

            $assignment = EmployeeSalaryAssignment::create($assignmentData);

            if ($request->has('components')) {
                $syncData = [];
                foreach ($request->components as $compId => $data) {
                    $syncData[$compId] = [
                        'amount' => $data['amount'] ?? 0,
                        'sort_order' => $data['sort_order'] ?? 0
                    ];
                }
                $assignment->components()->sync($syncData);
            }

            return redirect()->route('payroll.assignments.index')
                ->with('success', 'Salary Assignment created successfully.');
        });
    }

    public function getStructureComponents(SalaryStructure $structure)
    {
        $structure->load('components');
        $settings = [
            'pf_employee_percent' => \App\Models\Setting::get('payroll_pf_employee_percent', 12),
            'pf_employer_percent' => \App\Models\Setting::get('payroll_pf_employer_percent', 12),
            'pf_wage_limit' => \App\Models\Setting::get('payroll_pf_wage_limit', 15000),
            'esi_employee_percent' => \App\Models\Setting::get('payroll_esi_employee_percent', 0.75),
            'esi_employer_percent' => \App\Models\Setting::get('payroll_esi_employer_percent', 3.25),
            'esi_wage_limit' => \App\Models\Setting::get('payroll_esi_wage_limit', 21000),
        ];

        return response()->json([
            'components' => $structure->components,
            'settings' => $settings
        ]);
    }

    public function getEmployeeAssignment(Employee $employee)
    {
        $assignment = EmployeeSalaryAssignment::with('components')
            ->where('employee_id', $employee->id)
            ->where('status', true)
            ->first();

        return response()->json([
            'exists' => $assignment ? true : false,
            'assignment' => $assignment,
            'components' => $assignment ? $assignment->components->pluck('pivot.amount', 'id')->toArray() : [],
            'employee' => [
                'pf_no' => $employee->pf_no,
                'esi_no' => $employee->esi_no,
                'wwf_no' => $employee->wwf_no,
                'uan_no' => $employee->uan_no,
                'bank_name' => $employee->bank_name,
                'bank_branch' => $employee->bank_branch,
                'beneficiary_name' => $employee->beneficiary_name,
                'account_number' => $employee->account_number,
                'ifsc' => $employee->ifsc,
                'transaction_type' => $employee->transaction_type,
            ]
        ]);
    }

    public function getLastProcessedDateApi(Employee $employee)
    {
        $date = $this->getLastProcessedDate($employee->id);
        return response()->json([
            'last_processed_date' => $date ? $date->format('Y-m-d') : null,
            'formatted_date' => $date ? $date->format('F Y') : null
        ]);
    }

    public function edit(EmployeeSalaryAssignment $assignment)
    {
        $assignment->load('components', 'employee', 'structure.components');
        $employees = Employee::all();
        $structures = SalaryStructure::all();
        
        $settings = [
            'pf_employee_percent' => \App\Models\Setting::get('payroll_pf_employee_percent', 12),
            'pf_employer_percent' => \App\Models\Setting::get('payroll_pf_employer_percent', 12),
            'pf_wage_limit' => \App\Models\Setting::get('payroll_pf_wage_limit', 15000),
            'esi_employee_percent' => \App\Models\Setting::get('payroll_esi_employee_percent', 0.75),
            'esi_employer_percent' => \App\Models\Setting::get('payroll_esi_employer_percent', 3.25),
            'esi_wage_limit' => \App\Models\Setting::get('payroll_esi_wage_limit', 21000),
        ];

        $lastProcessed = $this->getLastProcessedDate($assignment->employee_id);
        $isLocked = false;

        $allComponents = $assignment->structure?->components ?? collect();
        
        // Order structure components by the saved sort order in the assignment
        $savedOrder = $assignment->components->pluck('pivot.sort_order', 'id')->toArray();
        if (!empty($savedOrder)) {
            $allComponents = $allComponents->sortBy(function($comp) use ($savedOrder) {
                return $savedOrder[$comp->id] ?? 999;
            })->values();
        }
        
        $savedAmounts = $assignment->components->pluck('pivot.amount', 'id')->toArray();
        return view('payroll.assignments.edit', compact(
            'assignment', 'employees', 'structures', 'settings', 
            'allComponents', 'savedAmounts', 'isLocked'
        ));
    }

    public function update(Request $request, EmployeeSalaryAssignment $assignment)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_structure_id' => 'required|exists:salary_structures,id',
            'base_amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'status' => 'required|boolean',
            'monthly_ctc' => 'nullable|numeric|min:0',
            'annual_ctc' => 'nullable|numeric|min:0',
            'pf_eligible' => 'nullable|boolean',
            'esi_eligible' => 'nullable|boolean',
            'components' => 'nullable|array',
        ]);

        $lastProcessed = $this->getLastProcessedDate($request->employee_id);
        if ($lastProcessed && \Carbon\Carbon::parse($request->effective_date)->lte($lastProcessed) && $request->salary_structure_id != $assignment->salary_structure_id) {
                        $nextMonth = $lastProcessed->copy()->addMonth()->format('F Y');
            return back()->withInput()->with('error', 'Payrole alredy procced on ' . $lastProcessed->format('F ,Y') . ' for employee. you need to change effective date ' . $nextMonth . '.');

        }

        return \DB::transaction(function () use ($request, $assignment) {
            if ($request->status) {
                // Deactivate other assignments for this employee
                EmployeeSalaryAssignment::where('employee_id', $request->employee_id)
                    ->where('id', '!=', $assignment->id)
                    ->update(['status' => false]);
            }

            $assignmentData = $request->only([
                'employee_id',
                'salary_structure_id',
                'base_amount',
                'effective_date',
                'monthly_ctc',
                'annual_ctc',
                'status'
            ]);
            $assignmentData['pf_eligible'] = $request->has('pf_eligible') ? 1 : 0;
            $assignmentData['esi_eligible'] = $request->has('esi_eligible') ? 1 : 0;

            $assignment->update($assignmentData);

            if ($request->has('components')) {
                $syncData = [];
                foreach ($request->components as $compId => $data) {
                    $syncData[$compId] = [
                        'amount' => $data['amount'] ?? 0,
                        'sort_order' => $data['sort_order'] ?? 0
                    ];
                }
                $assignment->components()->sync($syncData);
            }

            return redirect()->route('payroll.assignments.index')
                ->with('success', 'Salary Assignment updated successfully.');
        });
    }

    public function destroy(EmployeeSalaryAssignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('payroll.assignments.index')
            ->with('success', 'Salary Assignment deleted successfully.');
    }

    public function updateStatutory(Request $request, Employee $employee)
    {
        $employee->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "Employee details updated successfully",
            "employee" => $employee
        ]);
    }

    private function getLastProcessedDate($employeeId)
    {
        $lastBatch = \App\Models\PayrollBatch::whereHas('entries', function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        if ($lastBatch) {
            return \Carbon\Carbon::create($lastBatch->year, $lastBatch->month, 1)->endOfMonth();
        }

        return null;
    }
}
