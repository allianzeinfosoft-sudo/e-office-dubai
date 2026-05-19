<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollEntry;
use App\Services\Payroll\PayslipGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeePayrollController extends Controller
{
    protected $payslipGenerator;
    
    public function __construct(PayslipGenerator $payslipGenerator)
    {
        $this->payslipGenerator = $payslipGenerator;
    }

    public function index()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'Employee profile not found.');
        }
        $payslips = PayrollEntry::where('employee_id', $employee->id)
            ->whereHas('batch', function ($q) {
            $q->whereIn('status', ['approved', 'paid']);
        })->with('batch')
            ->latest()
            ->paginate(12);
        return view('payroll.employee.index', compact('payslips'));
    }

    public function download(PayrollEntry $entry)
    {
        $employee = Auth::user()->employee;
        // Security check: Ensure employee can only download their own payslip
        if (!$employee || $entry->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to payslip.');
        }
        // Ensure payslip is approved
        if ($entry->batch->status === 'draft') {
            abort(403, 'Payslip not yet released.');
        }
        $pdf = $this->payslipGenerator->generate($entry);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('payslip_' . date("F", mktime(0, 0, 0, $entry->batch->month, 10)) . '_' . $entry->batch->year . '.pdf');
    }

    public function yearlyStatement(Request $request)
    {
        $employee = Auth::user()->employee;
        $year = $request->get('year', date('Y'));
        $entries = PayrollEntry::where('employee_id', $employee->id)
            ->whereHas('batch', function ($q) use ($year) {
            $q->where('year', $year)->where('status', 'approved');
        })
            ->with('batch')
            ->get();
        $summary = [
            'gross' => $entries->sum('gross_salary'),
            'pf' => $entries->sum('pf_amount'),
            'esi' => $entries->sum('esi_amount'),
            'tds' => $entries->sum('tds_amount'),
            'deductions' => $entries->sum('total_deductions'),
            'lop' => $entries->sum('lop_amount'),
            'net' => $entries->sum('net_salary'),
        ];
        return view('payroll.employee.yearly', compact('entries', 'summary', 'year'));
    }
}