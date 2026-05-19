<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class SalaryRegisterSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $schemeName;
    protected $data;

    public function __construct($schemeName, $data)
    {
        $this->schemeName = $schemeName;
        $this->data = $data;
    }

    public function title(): string
    {
        // Sheet names have a max length
        return substr(str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $this->schemeName), 0, 31);
    }

    public function collection()
    {
        return $this->data['rows'];
    }

    public function headings(): array
    {
        $earningsCount = count($this->data['earnings_headers']);
        $deductionsCount = count($this->data['deductions_headers']);
        $totalDynamicCount = $earningsCount + $deductionsCount;
        
        $dynamicBlanks = array_fill(0, max(0, $totalDynamicCount - 1), '');

        $topHeader = array_merge(
            ['Employee Details', '', '', '2. Emoluments', '', '', 'Rate of Wages Actually Paid', '', '', 'Work Details', '', 'Salary Calculation', '', 'Salary Split', '', 'Statutory Deductions', ''],
            ['Additions & Deductions'],
            $dynamicBlanks,
            ['Final Salary Calculation', '', '', '', '', 'Extra']
        );

        $additionsHeaders = array_map(function($h) { return $h . ' (Add)'; }, $this->data['earnings_headers']);
        $deductionsHeaders = array_map(function($h) { return $h . ' (Ded)'; }, $this->data['deductions_headers']);

        $bottomHeader = array_merge(
            [
                'Sl No', 'Emp. Code', 'Name',
                'Basic + DA', 'HRA', 'Total Minimum Wage Payable',
                'Basic + DA', 'HRA', 'Total',
                'Payable Days', 'OT',
                'Gross Payable', 'Grand Total',
                'PF Salary', 'ESI Salary',
                'PF', 'ESI'
            ],
            $additionsHeaders,
            $deductionsHeaders,
            [
                'Total Deductions', 'Salary Part A', 'Salary Part B', 'Round Off', 'Net Payment',
                'Remarks'
            ]
        );

        return [$topHeader, $bottomHeader];
    }

    public function map($row): array
    {
        $mappedRow = [
            $row['sl_no'],
            $row['emp_code'],
            $row['name'],
            $row['min_basic_da'],
            $row['min_hra'],
            $row['min_total_payable'],
            $row['actual_basic_da'],
            $row['actual_hra'],
            $row['actual_total'],
            $row['payable_days'],
            $row['ot'],
            $row['gross_payable'],
            $row['grand_total'],
            $row['pf_salary'],
            $row['esi_salary'],
            $row['pf'],
            $row['esi'],
        ];

        foreach($this->data['earnings_headers'] as $earn) {
            $mappedRow[] = $row['dynamic_earnings'][$earn] ?? 0;
        }

        foreach($this->data['deductions_headers'] as $ded) {
            $mappedRow[] = $row['dynamic_deductions'][$ded] ?? 0;
        }

        $mappedExtra = [
            $row['total_deductions'],
            $row['salary_part_a'],
            $row['salary_part_b'],
            $row['round_off'],
            $row['net_payment'],
            $row['remarks']
        ];

        return array_merge($mappedRow, $mappedExtra);
    }

    public function styles(Worksheet $sheet)
    {
        $earningsCount = count($this->data['earnings_headers']);
        $deductionsCount = count($this->data['deductions_headers']);
        $totalDynamicCount = $earningsCount + $deductionsCount;
        $lastColumn = $this->getExcelColumnName(23 + $totalDynamicCount);

        // Header Style
        $sheet->getStyle("A1:{$lastColumn}2")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastColumn}2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE9E9E9');
        $sheet->getStyle("A1:{$lastColumn}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Borders for all data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:{$lastColumn}{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Alignments
        $sheet->getStyle("D3:{$lastColumn}{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        return [
            1 => ['font' => ['size' => 12]],
            2 => ['font' => ['size' => 10]],
        ];
    }

    private function getExcelColumnName($index)
    {
        $letters = '';
        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $letters = chr(65 + $remainder) . $letters;
            $index = intval(($index - $remainder) / 26);
        }
        return $letters;
    }
}
