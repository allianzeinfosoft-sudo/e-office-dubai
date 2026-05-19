<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalaryRegisterExport implements WithMultipleSheets
{
    protected $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->reportData as $schemeName => $data) {
            $sheets[] = new SalaryRegisterSheetExport($schemeName, $data);
        }

        return $sheets;
    }
}
