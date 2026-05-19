<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Register - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 7px;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            width: 80px;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .report-title {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .period {
            font-size: 10px;
            margin: 5px 0;
        }
        table.register-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        table.register-table th, table.register-table td {
            border: 0.5px solid #000;
            padding: 2px 3px;
            word-wrap: break-word;
        }
        table.register-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .section-header {
            background-color: #e9e9e9;
            font-weight: bold;
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
        .bg-light {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
        }
        @page {
            margin: 1cm;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @foreach($registerData as $schemeName => $data)
        <div class="header">
            <table>
                <tr>
                    <td width="10%">
                        @if(file_exists($companyLogo))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($companyLogo)) }}" class="logo">
                        @endif
                    </td>
                    <td width="90%" class="text-center">
                        <h1 class="company-name">{{ $companyName }}</h1>
                        <h2 class="report-title">Salary Register</h2>
                        <div class="period">Scheme: {{ $schemeName }} | Month: {{ $monthName }} {{ $year }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="register-table">
            <thead>
                <tr>
                    <th colspan="2">Emp Details</th>
                    <th colspan="3">2. Emoluments</th>
                    <th colspan="3">Actual Wage</th>
                    <th colspan="2">Work</th>
                    <th colspan="2">Salary Calc</th>
                    <th colspan="2">Statutory</th>
                    @php
                        $addDeductCount = count($data['earnings_headers']) + count($data['deductions_headers']);
                    @endphp
                    @if($addDeductCount > 0)
                        <th colspan="{{ $addDeductCount }}">Additions & Deductions</th>
                    @endif
                    <th colspan="4">Final</th>
                </tr>
                <tr>
                    <th>Code</th>
                    <th>Name</th>

                    <th>B+DA</th>
                    <th>HRA</th>
                    <th>Total</th>

                    <th>B+DA</th>
                    <th>HRA</th>
                    <th>Total</th>

                    <th>Days</th>
                    <th>OT</th>

                    <th>Gross</th>
                    <th>G.Total</th>

                    <th>PF</th>
                    <th>ESI</th>

                    @foreach($data['earnings_headers'] as $earn)
                        <th>{{ substr($earn, 0, 5) }}(A)</th>
                    @endforeach
                    @foreach($data['deductions_headers'] as $ded)
                        <th>{{ substr($ded, 0, 5) }}(D)</th>
                    @endforeach

                    <th>Ded</th>
                    <th>P-1</th>
                    <th>P-2</th>
                    <th>Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['rows'] as $row)
                    <tr>
                        <td>{{ $row['emp_code'] }}</td>
                        <td class="fw-bold">{{ $row['name'] }}</td>

                        <td class="text-end">{{ number_format($row['min_basic_da'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['min_hra'], 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row['min_total_payable'], 2) }}</td>

                        <td class="text-end">{{ number_format($row['actual_basic_da'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['actual_hra'], 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row['actual_total'], 2) }}</td>

                        <td class="text-center">{{ $row['payable_days'] }}</td>
                        <td class="text-center">{{ $row['ot'] }}</td>

                        <td class="text-end">{{ number_format($row['gross_payable'], 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row['grand_total'], 2) }}</td>

                        <td class="text-end">{{ number_format($row['pf'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['esi'], 2) }}</td>

                        @foreach($data['earnings_headers'] as $earn)
                            <td class="text-end">{{ number_format($row['dynamic_earnings'][$earn], 2) }}</td>
                        @endforeach
                        @foreach($data['deductions_headers'] as $ded)
                            <td class="text-end">{{ number_format($row['dynamic_deductions'][$ded], 2) }}</td>
                        @endforeach

                        <td class="text-end fw-bold">{{ number_format($row['total_deductions'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['salary_part_a'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['salary_part_b'], 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row['net_payment'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <tr class="fw-bold">
                    <td colspan="2" class="text-end">TOTAL:</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('min_basic_da'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('min_hra'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('min_total_payable'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('actual_basic_da'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('actual_hra'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('actual_total'), 2) }}</td>
                    <td class="text-center">{{ $data['rows']->sum('payable_days') }}</td>
                    <td class="text-center">{{ $data['rows']->sum('ot') }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('gross_payable'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('grand_total'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('pf'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('esi'), 2) }}</td>
                    @foreach($data['earnings_headers'] as $earn)
                        <td class="text-end">{{ number_format($data['rows']->sum(function($r) use ($earn) { return $r['dynamic_earnings'][$earn]; }), 2) }}</td>
                    @endforeach
                    @foreach($data['deductions_headers'] as $ded)
                        <td class="text-end">{{ number_format($data['rows']->sum(function($r) use ($ded) { return $r['dynamic_deductions'][$ded]; }), 2) }}</td>
                    @endforeach
                    <td class="text-end">{{ number_format($data['rows']->sum('total_deductions'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('salary_part_a'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('salary_part_b'), 2) }}</td>
                    <td class="text-end">{{ number_format($data['rows']->sum('net_payment'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="footer">
        Printed on: {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
