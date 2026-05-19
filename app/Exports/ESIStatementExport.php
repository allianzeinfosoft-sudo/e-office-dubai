<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ESIStatementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $month;
    protected $year;
    protected $esiEmployerPercent;

    public function __construct($data, $month, $year, $esiEmployerPercent = 3.25)
    {
        $this->data = $data;
        $this->month = $month;
        $this->year = $year;
        $this->esiEmployerPercent = $esiEmployerPercent;
    }

    public function collection()
    {
        $totalEsiSalary      = $this->data->sum('esi_salary');
        $totalEmployeeContri = $this->data->sum('employee_contri');
        $employerContriCalc  = round($totalEsiSalary * ($this->esiEmployerPercent / 100));
        $grandTotal          = $employerContriCalc + $totalEmployeeContri;

        $dataWithTotal = clone $this->data;

        // Total row
        $dataWithTotal->push([
            'sl_no'           => '',
            'emp_code'        => '',
            'name'            => 'Total',
            'esi_salary'      => $totalEsiSalary,
            'employee_contri' => $totalEmployeeContri,
        ]);

        // Employer Contribution row
        $dataWithTotal->push([
            'sl_no'           => '',
            'emp_code'        => '',
            'name'            => 'Employer Contribution (' . $this->esiEmployerPercent . '%)',
            'esi_salary'      => '',
            'employee_contri' => $employerContriCalc,
        ]);

        // Grand Total row
        $dataWithTotal->push([
            'sl_no'           => '',
            'emp_code'        => '',
            'name'            => 'Grand Total',
            'esi_salary'      => '',
            'employee_contri' => $grandTotal,
        ]);

        return $dataWithTotal;
    }

    public function title(): string
    {
        return 'ESI Statement';
    }

    public function styles(Worksheet $sheet)
    {
        $dataCount     = $this->data->count();
        $totalRow      = $dataCount + 6; // 5 heading rows + data rows + 1
        $erContriRow   = $totalRow + 1;
        $grandTotalRow = $totalRow + 2;

        return [
            1              => ['font' => ['bold' => true, 'size' => 14]],
            2              => ['font' => ['bold' => true, 'size' => 12]],
            5              => ['font' => ['bold' => true]],
            $totalRow      => ['font' => ['bold' => true]],
            $erContriRow   => ['font' => ['bold' => true]],
            $grandTotalRow => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        $companyName = \App\Models\Setting::get('company_name', 'ATS');
        $monthName   = date("F", mktime(0, 0, 0, $this->month, 10));

        return [
            [$companyName],
            ['ESI STATEMENT FOR ' . strtoupper($monthName) . ' ' . $this->year],
            [''],
            [''],
            [
                'Sl. No.',
                'Emp. Code',
                'Name',
                'ESI Salary',
                'Employee Contri.'
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row['sl_no'],
            $row['emp_code'],
            $row['name'],
            $row['esi_salary'],
            $row['employee_contri'],
        ];
    }
}
