<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class WWFStatementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
        $totalEmployeeContri = $this->data->sum('employee_contri');
        $totalEmployerContri = $this->data->sum('employer_contri');

        $dataWithTotal = clone $this->data;

        $dataWithTotal->push([
            'sl_no'           => '',
            'emp_code'        => '',
            'name'            => 'Total',
            'employee_contri' => $totalEmployeeContri,
            'employer_contri' => $totalEmployerContri,
        ]);

        return $dataWithTotal;
    }

    public function title(): string
    {
        return 'WWF Statement';
    }

    public function styles(Worksheet $sheet)
    {
        $totalRow = $this->data->count() + 6; // 5 heading rows + data + 1
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
            ['WWF STATEMENT FOR ' . strtoupper($monthName) . ' ' . $this->year],
            [''],
            [''],
            [
                'Sl. No.',
                'Emp. Code',
                'Name',
                'Employee Contri.',
                'Employer Contri.'
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row['sl_no'],
            $row['emp_code'],
            $row['name'],
            $row['employee_contri'],
            $row['employer_contri'],
        ];
    }
}
