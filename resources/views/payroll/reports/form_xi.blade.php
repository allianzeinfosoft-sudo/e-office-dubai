@extends('layouts.app')

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container bg-eoffice">
        <x-menu />
        <div class="layout-page">
            <x-header />
            <div class="content-wrapper">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light">Payroll / Reports /</span> Register of Wages (FORM XI)
                    </h4>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('payroll.reports.form-xi') }}" class="row g-3 align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label">Month</label>
                                    <select name="month" class="form-select">
                                        @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                            {{ date("F", mktime(0, 0, 0, $i, 10)) }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Year</label>
                                    <select name="year" class="form-select">
                                        @foreach($years as $yr)
                                        <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Salary Structure</label>
                                    <select name="structure_id" class="form-select" required>
                                        <option value="" disabled {{ !$structureId ? 'selected' : '' }}>Select Structure</option>
                                        @foreach($structures as $struct)
                                        <option value="{{ $struct->id }}" {{ $structureId == $struct->id ? 'selected' : '' }}>{{ $struct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search me-1"></i> Generate
                                    </button>
                                    <button type="submit" name="export" value="excel" class="btn btn-success">
                                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                                    </button>
                                    <a href="{{ route('payroll.reports.form-xi.export-pdf', request()->all()) }}" class="btn btn-danger" target="_blank">
                                        <i class="ti ti-file-type-pdf me-1"></i> Export PDF
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header text-center pb-0">
                            <h5 class="mb-1">FORM XI</h5>
                            <h6 class="mb-1">REGISTER OF WAGES</h6>
                            <p class="mb-3">[See Rule 29 (1)]</p>
                            
                            <div class="row text-start mb-3">
                                <div class="col-md-4">
                                    <strong>Name of Establishment:</strong> {{ $companyName }}<br>
                                    <strong>Place:</strong> {{ $branch }}
                                </div>
                                <div class="col-md-4 text-center">
                                    <strong>Wage Period:</strong> 01-{{ sprintf('%02d', $month) }}-{{ $year }} to {{ \Carbon\Carbon::create($year, $month)->endOfMonth()->format('d-m-Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table class="table table-bordered table-striped border-top" style="white-space: nowrap; font-size: 0.8rem;">
                                <thead class="table-light">
                                    <tr>
                                        <!-- Phase 1: Employee Details -->
                                        <th colspan="7" class="text-center bg-primary text-white border-end">Employee Details</th>
                                        <!-- Phase 2: Minimum Rate -->
                                        <th colspan="3" class="text-center bg-info text-white border-end">Emoluments</th>
                                        <!-- Phase 3: Actual Rate -->
                                        <th colspan="3" class="text-center bg-success text-white border-end">Rate of Wages Actually Paid</th>
                                        <!-- Phase 4: Work & Earnings -->
                                        <th colspan="4" class="text-center bg-secondary text-white border-end">Work & Earnings Details</th>
                                        <!-- Phase 5: Statutory Base -->
                                        <th colspan="2" class="text-center bg-warning text-dark border-end">Statutory Salary Base</th>
                                        <!-- Phase 6: Deductions -->
                                        <th colspan="8" class="text-center bg-danger text-white border-end">Deductions</th>
                                        <!-- Phase 7: Net Pay -->
                                        <th colspan="2" class="text-center bg-dark text-white border-end">Net Pay</th>
                                        <!-- Phase 8: Payment Info -->
                                        <th colspan="2" class="text-center bg-primary text-white">Payment Info</th>
                                    </tr>
                                    <tr class="text-center">
                                        <!-- Emp -->
                                        <th>Sl. No.</th>
                                        <th>PF No.</th>
                                        <th>UAN</th>
                                        <th>ESI No.</th>
                                        <th>WWF No.</th>
                                        <th>Employee Name</th>
                                        <th>DOJ</th>
                                        <!-- Min -->
                                        <th>Basic + DA</th>
                                        <th>HRA</th>
                                        <th>Total Salary Payable</th>
                                        <!-- Actual -->
                                        <th>Basic + DA</th>
                                        <th>HRA</th>
                                        <th>Total</th>
                                        <!-- Work -->
                                        <th>Payable Days</th>
                                        <th>Holiday Wages / OT / Others</th>
                                        <th>Incentive</th>
                                        <th>Gross Salary Payable</th>
                                        <!-- Base -->
                                        <th>PF Salary</th>
                                        <th>ESI Salary</th>
                                        <!-- Ded -->
                                        <th>PF</th>
                                        <th>ESI</th>
                                        <th>WWF</th>
                                        <th>PT</th>
                                        <th>Salary Advance</th>
                                        <th>TDS</th>
                                        <th>Other Deductions</th>
                                        <th>Total Deductions</th>
                                        <!-- Net -->
                                        <th>Net Salary</th>
                                        <th>Rounded Value</th>
                                        <!-- Payment -->
                                        <th>Date of Payment</th>
                                        <th>Employee Signature</th>
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
                                        <td>{{ $row['name'] }}</td>
                                        <td>{{ $row['doj'] }}</td>

                                        <td class="text-end">{{ number_format($row['min_basic_da'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['min_hra'], 2) }}</td>
                                        <td class="text-end"><strong>{{ number_format($row['min_total'], 2) }}</strong></td>

                                        <td class="text-end">{{ number_format($row['actual_basic_da'], 2) }}</td>
                                        <td class="text-end">{{ number_format($row['actual_hra'], 2) }}</td>
                                        <td class="text-end"><strong>{{ number_format($row['actual_total'], 2) }}</strong></td>

                                        <td class="text-center">{{ $row['payable_days'] }}</td>
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
                                        <td class="text-end"><strong>{{ number_format($row['total_deductions'], 2) }}</strong></td>

                                        <td class="text-end">{{ number_format($row['net_salary'], 2) }}</td>
                                        <td class="text-end font-weight-bold"><strong>{{ number_format($row['rounded_value'], 2) }}</strong></td>

                                        <td>{{ $row['payment_date'] }}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
