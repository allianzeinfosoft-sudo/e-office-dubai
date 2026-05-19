<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WWF Statement - {{ $monthName }} {{ $year }}</title>
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
        .text-end  { text-align: right !important; }
        .text-start { text-align: left !important; }
        .fw-bold   { font-weight: bold; }
        .footer { margin-top: 30px; width: 100%; }
        .footer table { border: none; }
        .footer td   { border: none; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="report-title">WWF STATEMENT FOR {{ strtoupper($monthName) }} {{ $year }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">Sl. No.</th>
                <th width="12%">Emp. Code</th>
                <th width="43%">Name</th>
                <th width="20%">Employee Contri.</th>
                <th width="20%">Employer Contri.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wwfData as $row)
            <tr>
                <td>{{ $row['sl_no'] }}</td>
                <td>{{ $row['emp_code'] }}</td>
                <td class="text-start">{{ $row['name'] }}</td>
                <td class="text-end">{{ number_format($row['employee_contri'], 2) }}</td>
                <td class="text-end">{{ number_format($row['employer_contri'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="fw-bold">
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" class="text-end">TOTAL:</td>
                <td class="text-end">{{ number_format($wwfData->sum('employee_contri'), 2) }}</td>
                <td class="text-end">{{ number_format($wwfData->sum('employer_contri'), 2) }}</td>
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
