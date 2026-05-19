<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form XI - Register of Wages - {{ $month }}/{{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 6px;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
            text-align: center;
        }
        .report-title {
            font-size: 10px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .rule-text {
            font-size: 8px;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 8px;
        }
        table.form-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        table.form-table th, table.form-table td {
            border: 0.5px solid #000;
            padding: 2px 1px;
            word-wrap: break-word;
            text-align: center;
        }
        table.form-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-end {
            text-align: right !important;
        }
        .text-start {
            text-align: left !important;
        }
        .fw-bold {
            font-weight: bold;
        }
        @page {
            margin: 0.5cm;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 7px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-title">FORM XI</div>
        <div class="report-title">REGISTER OF WAGES</div>
        <div class="rule-text">[See Rule 29 (1)]</div>
        
        <table class="info-table">
            <tr>
                <td class="text-start" width="40%">
                    <strong>Name of Establishment:</strong> {{ $companyName }}<br>
                    <strong>Place:</strong> {{ $branch }}
                </td>
                <td class="text-end" width="60%">
                    <strong>Wage Period:</strong> {{ $startDate }} to {{ $endDate }}
                </td>
            </tr>
        </table>
    </div>

    <table class="form-table">
        <thead>
            <tr>
                <th colspan="7">Employee Details</th>
                <th colspan="3">Emoluments</th>
                <th colspan="3">Rate of Wages Actually Paid</th>
                <th colspan="4">Work & Earnings Details</th>
                <th colspan="2">Statutory Salary Base</th>
                <th colspan="8">Deductions</th>
                <th colspan="2">Net Pay</th>
                <th colspan="2">Payment Info</th>
            </tr>
            <tr>
                <th>Sl. No.</th>
                <th>PF No.</th>
                <th>UAN</th>
                <th>ESI No.</th>
                <th>WWF No.</th>
                <th>Employee Name</th>
                <th>DOJ</th>
                
                <th>B+DA</th>
                <th>HRA</th>
                <th>Total</th>
                
                <th>B+DA</th>
                <th>HRA</th>
                <th>Total</th>
                
                <th>Payable Days</th>
                <th>Holiday/OT/Others</th>
                <th>Incentive</th>
                <th>Gross Payable</th>
                
                <th>PF Sal</th>
                <th>ESI Sal</th>
                
                <th>PF</th>
                <th>ESI</th>
                <th>WWF</th>
                <th>PT</th>
                <th>Adv</th>
                <th>TDS</th>
                <th>Other</th>
                <th>Total Ded</th>
                
                <th>Net Sal</th>
                <th>Rounded</th>
                
                <th>Date of Payment</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($formXiData as $row)
            <tr>
                <td>{{ $row['sl_no'] }}</td>
                <td>{{ $row['pf_no'] }}</td>
                <td>{{ $row['uan'] }}</td>
                <td>{{ $row['esi_no'] }}</td>
                <td>{{ $row['wwf_no'] }}</td>
                <td class="text-start fw-bold">{{ $row['name'] }}</td>
                <td>{{ $row['doj'] }}</td>

                <td class="text-end">{{ number_format($row['min_basic_da'], 2) }}</td>
                <td class="text-end">{{ number_format($row['min_hra'], 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($row['min_total'], 2) }}</td>

                <td class="text-end">{{ number_format($row['actual_basic_da'], 2) }}</td>
                <td class="text-end">{{ number_format($row['actual_hra'], 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($row['actual_total'], 2) }}</td>

                <td>{{ $row['payable_days'] }}</td>
                <td class="text-end">{{ number_format($row['ot_holiday_others'], 2) }}</td>
                <td class="text-end">{{ number_format($row['incentive'], 2) }}</td>
                <td class="text-end">{{ number_format($row['gross_payable'], 2) }}</td>

                <td class="text-end">{{ number_format($row['pf_salary'], 2) }}</td>
                <td class="text-end">{{ number_format($row['esi_salary'], 2) }}</td>

                <td class="text-end">{{ number_format($row['pf'], 2) }}</td>
                <td class="text-end">{{ number_format($row['esi'], 2) }}</td>
                <td class="text-end">{{ number_format($row['wwf'], 2) }}</td>
                <td class="text-end">{{ number_format($row['pt'], 2) }}</td>
                <td class="text-end">{{ number_format($row['advance'], 2) }}</td>
                <td class="text-end">{{ number_format($row['tds'], 2) }}</td>
                <td class="text-end">{{ number_format($row['other_deductions'], 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($row['total_deductions'], 2) }}</td>

                <td class="text-end">{{ number_format($row['net_salary'], 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($row['rounded_value'], 2) }}</td>

                <td>{{ $row['payment_date'] }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #f9f9f9; font-weight: bold;">
            <tr>
                <td colspan="7" class="text-end">TOTAL:</td>
                <td class="text-end">{{ number_format($formXiData->sum('min_basic_da'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('min_hra'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('min_total'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('actual_basic_da'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('actual_hra'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('actual_total'), 2) }}</td>
                <td>{{ $formXiData->sum('payable_days') }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('ot_holiday_others'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('incentive'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('gross_payable'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('pf_salary'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('esi_salary'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('pf'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('esi'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('wwf'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('pt'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('advance'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('tds'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('other_deductions'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('total_deductions'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('net_salary'), 2) }}</td>
                <td class="text-end">{{ number_format($formXiData->sum('rounded_value'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Printed on: {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
