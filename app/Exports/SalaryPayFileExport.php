<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalaryPayFileExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;
    protected $companyBank;
    protected $month;
    protected $year;
    protected $generatedDate;

    public function __construct($data, $companyBank, $month, $year, $generatedDate = null)
    {
        $this->data = $data;
        $this->companyBank = $companyBank;
        $this->month = $month;
        $this->year = $year;
        $this->generatedDate = $generatedDate ?? date('d-m-Y');
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Transaction Type',
            'Beneficiary Code',
            'Value Date',
            'Debit A/C Number',
            'Transaction Amount',
            'Beneficiary Name',
            'Beneficiary A/c No.',
            'IFSC Code',
            'Bene Email ID',
            'Bene Mobile No',
            'Customer Ref No.',
            'Payment Narration'
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row['transaction_type'] ?? '',
            '', // Beneficiary Code
            $this->generatedDate, // Value Date
            $this->companyBank->account_no ?? '', // Debit A/C Number
            $row['net_salary'] ?? 0, // Transaction Amount
            $row['full_name'] ?? '', // Beneficiary Name
            $row['account_number'] ?? '', // Beneficiary A/c No.
            $row['ifsc'] ?? '', // IFSC Code
            $row['personal_email'] ?? '', // Bene Email ID
            $row['phonenumber'] ?? '', // Bene Mobile No
            $row['customer_ref_no'], // Customer Ref No.
            'SALARY' // Payment Narration
        ];
    }
}
