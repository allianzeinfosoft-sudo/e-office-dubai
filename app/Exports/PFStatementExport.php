<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PFStatementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $month;
    protected $year;

    public function __construct($data, $month, $year)
    {
        $this->data  = $data;
        $this->month = $month;
        $this->year  = $year;
    }

    public function collection()
    {
        $dataWithTotal = clone $this->data;

        $dataWithTotal->push([
            'sl_no'           => '',
            'emp_code'        => '',
            'name'            => 'Total',
            'pf_salary'       => $this->data->sum('pf_salary'),
            'employee_contri' => $this->data->sum('employee_contri'),
            'employer_contri' => $this->data->sum('employer_contri'),
            'eps'             => $this->data->sum('eps'),
            'epf'             => $this->data->sum('epf'),
        ]);

        return $dataWithTotal;
    }

    public function title(): string
    {
        return 'PF Statement';
    }

    public function styles(Worksheet $sheet)
    {
        $totalRow = $this->data->count() + 6;
        return [
            1         => ['font' => ['bold' => true, 'size' => 14]],
            2         => ['font' => ['bold' => true, 'size' => 12]],
            5         => ['font' => ['bold' => true]],
            $totalRow => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        $companyName = \App\Models\Setting::get('company_name', 'ATS');
        $monthName   = date("F", mktime(0, 0, 0, $this->month, 10));

        return [
            [$companyName],
            ['PF STATEMENT FOR ' . strtoupper($monthName) . ' ' . $this->year],
            [''],
            [''],
            [
                'Sl. No.',
                'Emp. Code',
                'Name',
                'PF Salary',
                'Employee Contri. (12%)',
                'Employer Contri. (12%)',
                'EPS (8.33%)',
                'EPF (3.67%)',
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row['sl_no'],
            $row['emp_code'],
            $row['name'],
            $row['pf_salary'],
            $row['employee_contri'],
            $row['employer_contri'],
            $row['eps'],
            $row['epf'],
        ];
    }
}
