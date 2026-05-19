<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollBatch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    public function exportBankFile(PayrollBatch $batch)
    {
        $batch->load('entries.employee');

        $filename = "Bank_Transfer_" . date('F_Y', mktime(0, 0, 0, $batch->month, 10)) . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($batch) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, ['Employee Name', 'Beneficiary Name', 'Account Number', 'IFSC', 'Bank Name', 'Branch', 'Net Amount', 'Narration']);

            foreach ($batch->entries as $entry) {
                $employee = $entry->employee;
                fputcsv($file, [
                    $employee->full_name,
                    $employee->beneficiary_name ?? $employee->full_name,
                    $employee->account_number,
                    $employee->ifsc,
                    $employee->bank_name,
                    $employee->bank_branch,
                    number_format($entry->net_salary, 2, '.', ''),
                    "Salary " . date('M Y', mktime(0, 0, 0, $batch->month, 10))
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportSummary(PayrollBatch $batch)
    {
        $batch->load('entries.employee');

        $filename = "Payroll_Summary_" . date('F_Y', mktime(0, 0, 0, $batch->month, 10)) . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($batch) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Emp Code', 'Name', 'Gross', 'PF', 'ESI', 'TDS', 'Total Deductions', 'Er. Contrib', 'LOP Amount', 'Net Salary', 'CTC']);

            foreach ($batch->entries as $entry) {
                fputcsv($file, [
                    $entry->employee->employeeID,
                    $entry->employee->full_name,
                    number_format($entry->gross_salary, 2, '.', ''),
                    number_format($entry->pf_amount ?? 0, 2, '.', ''),
                    number_format($entry->esi_amount ?? 0, 2, '.', ''),
                    number_format($entry->tds_amount ?? 0, 2, '.', ''),
                    number_format($entry->total_deductions, 2, '.', ''),
                    number_format($entry->total_employer_contribution, 2, '.', ''),
                    number_format($entry->lop_amount, 2, '.', ''),
                    number_format($entry->net_salary, 2, '.', ''),
                    number_format($entry->ctc, 2, '.', '')
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

}
