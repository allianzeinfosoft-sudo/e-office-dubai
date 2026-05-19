<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ESI Statement - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10px;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .company-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
        }
        th {
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
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .footer table {
            border: none;
        }
        .footer td {
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="report-title">ESI STATEMENT FOR {{ strtoupper($monthName) }} {{ $year }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Sl. No.</th>
                <th width="15%">Emp. Code</th>
                <th width="40%">Name</th>
                <th width="17%">ESI Salary</th>
                <th width="18%">Employee Contri.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($esiData as $row)
            <tr>
                <td>{{ $row['sl_no'] }}</td>
                <td>{{ $row['emp_code'] }}</td>
                <td class="text-start">{{ $row['name'] }}</td>
                <td class="text-end">{{ number_format($row['esi_salary'], 2) }}</td>
                <td class="text-end">{{ number_format($row['employee_contri'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="fw-bold">
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" class="text-end">TOTAL:</td>
                <td class="text-end">{{ number_format($esiData->sum('esi_salary'), 2) }}</td>
                <td class="text-end">{{ number_format($esiData->sum('employee_contri'), 2) }}</td>
            </tr>
            @php
                $totalEsiSalary      = $esiData->sum('esi_salary');
                $totalEmployeeContri = $esiData->sum('employee_contri');
                $employerContriCalc  = round($totalEsiSalary * ($esiEmployerPercent / 100));
                $grandTotal          = $employerContriCalc + $totalEmployeeContri;
            @endphp
            <tr>
                <td colspan="4" class="text-end">EMPLOYER CONTRIBUTION ({{ $esiEmployerPercent }}%):</td>
                <td class="text-end">{{ number_format($employerContriCalc, 2) }}</td>
            </tr>
            <tr style="background-color: #fff3cd; font-weight: bold;">
                <td colspan="4" class="text-end">GRAND TOTAL:</td>
                <td class="text-end">{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td class="text-start">Printed on: {{ date('d-m-Y H:i:s') }}</td>
                <td class="text-end">Authorized Signatory</td>
            </tr>
        </table>
    </div>
</body>
</html>
