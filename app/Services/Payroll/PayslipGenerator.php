<?php

namespace App\Services\Payroll;

use App\Models\PayrollEntry;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PayslipGenerator
{
     /**
      * Generate PDF payslip for a payroll entry.
      */
     public function generate(PayrollEntry $entry)
     {
          $data = [
               'entry' => $entry->load('employee.department', 'employee.designation', 'employee.bankDetails', 'components'),
               'company_name' => Setting::get('company_name', config('app.name', 'E-Office')),
               'company_address' => Setting::get('company_address', ''),
               'company_logo' => public_path(Setting::get('company_logo', 'assets/img/branding/logo.png')),
          ];

          $pdf = Pdf::loadView('payroll.payslip', $data);
          $pdf->setPaper('A4', 'landscape');
          return $pdf;
     }

     /**
      * Save payslip to storage.
      */
     public function saveToStorage(PayrollEntry $entry)
     {
          $pdf = $this->generate($entry);
          $filename = 'payslip_' . $entry->employee_id . '_' . $entry->batch->month . '_' . $entry->batch->year . '.pdf';
          $path = 'payroll/payslips/' . $entry->batch->year . '/' . $entry->batch->month . '/' . $filename;

          Storage::disk('public')->put($path, $pdf->output());

          return $path;
     }
}
