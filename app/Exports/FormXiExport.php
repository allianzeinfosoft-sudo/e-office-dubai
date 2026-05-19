<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FormXiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $month;
    protected $year;

    public function __construct($data, $month, $year)
    {
        $this->data = $data;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['italic' => true]],
            8 => ['font' => ['bold' => true]],
            9 => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        $companyName = \App\Models\Setting::get('company_name', 'ATS');
        $toDate = \Carbon\Carbon::create($this->year, $this->month)->endOfMonth()->format('d-m-Y');
        
        return [
            ['FORM XI'],
            ['REGISTER OF WAGES'],
            ['[See Rule 29 (1)]'],
            [''],
            ['Name of Establishment:', $companyName],
            ['Place:', 'Main Office'],
            ['Wage Period:', "01-" . sprintf('%02d', $this->month) . "-" . $this->year . " to " . $toDate],
            [''],
            [
                'Employee Details', '', '', '', '', '', '',
                'Emoluments', '', '',
                'Rate of Wages Actually Paid', '', '',
                'Work & Earnings Details', '', '', '',
                'Statutory Salary Base', '',
                'Deductions', '', '', '', '', '', '', '',
                'Net Pay', '',
                'Payment Info', ''
            ],
            [
                'Sl. No.', 'PF No.', 'UAN', 'ESI No.', 'WWF No.', 'Employee Name', 'DOJ',
                'Basic + DA', 'HRA', 'Total Salary Payable',
                'Basic + DA', 'HRA', 'Total',
                'Payable Days', 'Holiday Wages / OT / Others', 'Incentive', 'Gross Salary Payable',
                'PF Salary', 'ESI Salary',
                'PF', 'ESI', 'WWF', 'PT', 'Salary Advance', 'TDS', 'Other Deductions', 'Total Deductions',
                'Net Salary', 'Rounded Value',
                'Date of Payment', 'Employee Signature'
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row['sl_no'],
            $row['pf_no'],
            $row['uan'],
            $row['esi_no'],
            $row['wwf_no'],
            $row['name'],
            $row['doj'],
            $row['min_basic_da'],
            $row['min_hra'],
            $row['min_total'],
            $row['actual_basic_da'],
            $row['actual_hra'],
            $row['actual_total'],
            $row['payable_days'],
            $row['ot_holiday_others'],
            $row['incentive'],
            $row['gross_payable'],
            $row['pf_salary'],
            $row['esi_salary'],
            $row['pf'],
            $row['esi'],
            $row['wwf'],
            $row['pt'],
            $row['advance'],
            $row['tds'],
            $row['other_deductions'],
            $row['total_deductions'],
            $row['net_salary'],
            $row['rounded_value'],
            $row['payment_date'],
            ''
        ];
    }
}
