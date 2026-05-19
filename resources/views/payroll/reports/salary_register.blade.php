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
                        <span class="text-muted fw-light">Payroll / Reports /</span> Salary Register
                    </h4>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('payroll.reports.salary-register') }}" class="row g-3 align-items-end">
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
                                        @if(!in_array(date('Y'), $years->toArray()))
                                        <option value="{{ date('Y') }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                                        @endif
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
                                <div class="col-md-3">
                                    <label class="form-label">Salary Type</label>
                                    <select name="salary_type" class="form-select">
                                        <option value="all" {{ $salaryType == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="statutory" {{ $salaryType == 'statutory' || $salaryType == 'pf' ? 'selected' : '' }}>Statutory Staff</option>
                                        <option value="non_statutory" {{ $salaryType == 'non_statutory' || $salaryType == 'non_pf' ? 'selected' : '' }}>Non-Statutory Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary d-block w-100">
                                        <i class="ti ti-search me-1"></i> Generate
                                    </button>
                                </div>
                                <div class="col-md-10 d-flex justify-content-end gap-2">
                                    <button type="submit" name="export" value="pdf" class="btn btn-label-secondary" formaction="{{ route('payroll.reports.salary-register.export-pdf') }}">
                                        <i class="ti ti-file-pdf me-1"></i> Export PDF
                                    </button>
                                    <button type="submit" name="export" value="excel" class="btn btn-success" formaction="{{ route('payroll.reports.salary-register.export') }}">
                                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @foreach($registerData as $schemeName => $data)
                    <div class="card mb-4">
                        <div class="card-header bg-label-primary">
                            <h5 class="m-0">{{ $schemeName }}</h5>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table class="datatables-basic table table-bordered table-striped border-top" style="white-space: nowrap;">
                                <thead class="table-light">
                                    <tr>
                                        <!-- Header groupings -->
                                        <th colspan="3" class="text-center bg-primary text-white border-end">1. Employee Details</th>
                                        <th colspan="3" class="text-center bg-info text-white border-end">2. Emoluments</th>
                                        <th colspan="3" class="text-center bg-success text-white border-end">3. Rate of Wages Actually Paid</th>
                                        <th colspan="2" class="text-center bg-secondary text-white border-end">4. Work Details</th>
                                        <th colspan="2" class="text-center bg-warning text-white border-end">5. Salary Calculation</th>
                                        <th colspan="2" class="text-center bg-danger text-white border-end">6. Salary Split</th>
                                        <th colspan="2" class="text-center bg-dark text-white border-end">7. Statutory Deductions</th>
                                        
                                        @php
                                            $addDeductCount = count($data['earnings_headers']) + count($data['deductions_headers']);
                                        @endphp
                                        @if($addDeductCount > 0)
                                        <th colspan="{{ $addDeductCount }}" class="text-center bg-info text-white border-end">8. Additions & Deductions</th>
                                        @else
                                        <th class="text-center bg-info text-white border-end">8. Additions & Deductions</th>
                                        @endif

                                        <th colspan="5" class="text-center bg-primary text-white border-end">9. Final Salary Calculation</th>
                                        <th class="text-center bg-secondary text-white">10. Extra</th>
                                    </tr>
                                    <tr style="font-size: 0.8rem;">
                                        <!-- Emloyee Details -->
                                        <th>Sl No</th>
                                        <th>Emp. Code</th>
                                        <th class="border-end">Name</th>

                                        <!-- Minimum Wages -->
                                        <th>Basic + DA</th>
                                        <th>HRA</th>
                                        <th class="border-end">Total Minimum Wage Payable</th>

                                        <!-- Actual Wages -->
                                        <th>Basic + DA</th>
                                        <th>HRA</th>
                                        <th class="border-end">Total</th>

                                        <!-- Work Details -->
                                        <th>Payable Days</th>
                                        <th class="border-end">OT</th>

                                        <!-- Salary Calculation -->
                                        <th>Gross Payable</th>
                                        <th class="border-end">Grand Total</th>

                                        <!-- Salary Split -->
                                        <th>PF Salary</th>
                                        <th class="border-end">ESI Salary</th>

                                        <!-- Statutory -->
                                        <th>PF</th>
                                        <th class="border-end">ESI</th>

                                        <!-- Additions & Deductions -->
                                        @if($addDeductCount > 0)
                                            @foreach($data['earnings_headers'] as $earn)
                                                <th>{{ $earn }} (Add)</th>
                                            @endforeach
                                            @foreach($data['deductions_headers'] as $index => $ded)
                                                <th class="{{ $index == count($data['deductions_headers']) - 1 ? 'border-end' : '' }} text-danger">{{ $ded }} (Ded)</th>
                                            @endforeach
                                        @else
                                            <th class="border-end text-muted">None</th>
                                        @endif

                                        <!-- Final Salary -->
                                        <th>Total Deductions</th>
                                        <th>Salary Part A</th>
                                        <th>Salary Part B</th>
                                        <th>Round Off</th>
                                        <th class="border-end">Net Payment</th>

                                        <!-- Extra -->
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['rows'] as $row)
                                    <tr style="font-size: 0.85rem;">
                                        <td>{{ $row['sl_no'] }}</td>
                                        <td>{{ $row['emp_code'] }}</td>
                                        <td class="fw-bold border-end">{{ $row['name'] }}</td>

                                        <td align="right">{{ number_format($row['min_basic_da'], 2) }}</td>
                                        <td align="right">{{ number_format($row['min_hra'], 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($row['min_total_payable'], 2) }}</td>

                                        <td align="right">{{ number_format($row['actual_basic_da'], 2) }}</td>
                                        <td align="right">{{ number_format($row['actual_hra'], 2) }}</td>
                                        <td align="right" class="border-end fw-bold">{{ number_format($row['actual_total'], 2) }}</td>

                                        <td align="center">{{ $row['payable_days'] }}</td>
                                        <td align="center" class="border-end">{{ $row['ot'] }}</td>

                                        <td align="right">{{ number_format($row['gross_payable'], 2) }}</td>
                                        <td align="right" class="border-end fw-bold">{{ number_format($row['grand_total'], 2) }}</td>

                                        <td align="right">{{ number_format($row['pf_salary'], 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($row['esi_salary'], 2) }}</td>

                                        <td align="right">{{ number_format($row['pf'], 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($row['esi'], 2) }}</td>

                                        @if($addDeductCount > 0)
                                            @foreach($data['earnings_headers'] as $earn)
                                                <td align="right">{{ number_format($row['dynamic_earnings'][$earn], 2) }}</td>
                                            @endforeach
                                            @foreach($data['deductions_headers'] as $index => $ded)
                                                <td align="right" class="{{ $index == count($data['deductions_headers']) - 1 ? 'border-end' : '' }} text-danger">{{ number_format($row['dynamic_deductions'][$ded], 2) }}</td>
                                            @endforeach
                                        @else
                                            <td class="border-end"></td>
                                        @endif

                                        <td align="right" class="text-danger fw-bold">{{ number_format($row['total_deductions'], 2) }}</td>
                                        <td align="right">{{ number_format($row['salary_part_a'], 2) }}</td>
                                        <td align="right">{{ number_format($row['salary_part_b'], 2) }}</td>
                                        <td align="right">{{ number_format($row['round_off'], 2) }}</td>
                                        <td align="right" class="border-end fw-bold text-success">{{ number_format($row['net_payment'], 2) }}</td>

                                        <td>{{ $row['remarks'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <!-- Need an exact colspan if empty, approximation -->
                                        <td colspan="50" class="text-center py-4">No records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                
                                @if(count($data['rows']) > 0)
                                <tfoot class="table-dark">
                                    <tr style="font-size: 0.85rem;" class="fw-bold">
                                        <td colspan="3" class="text-end border-end">TOTAL:</td>
                                        
                                        <td align="right">{{ number_format($data['rows']->sum('min_basic_da'), 2) }}</td>
                                        <td align="right">{{ number_format($data['rows']->sum('min_hra'), 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($data['rows']->sum('min_total_payable'), 2) }}</td>

                                        <td align="right">{{ number_format($data['rows']->sum('actual_basic_da'), 2) }}</td>
                                        <td align="right">{{ number_format($data['rows']->sum('actual_hra'), 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($data['rows']->sum('actual_total'), 2) }}</td>

                                        <td align="center">{{ $data['rows']->sum('payable_days') }}</td>
                                        <td align="center" class="border-end">{{ $data['rows']->sum('ot') }}</td>

                                        <td align="right">{{ number_format($data['rows']->sum('gross_payable'), 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($data['rows']->sum('grand_total'), 2) }}</td>

                                        <td align="right">{{ number_format($data['rows']->sum('pf_salary'), 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($data['rows']->sum('esi_salary'), 2) }}</td>

                                        <td align="right">{{ number_format($data['rows']->sum('pf'), 2) }}</td>
                                        <td align="right" class="border-end">{{ number_format($data['rows']->sum('esi'), 2) }}</td>

                                        @if($addDeductCount > 0)
                                            @foreach($data['earnings_headers'] as $earn)
                                                <td align="right">{{ number_format($data['rows']->sum(function($row) use ($earn) { return $row['dynamic_earnings'][$earn]; }), 2) }}</td>
                                            @endforeach
                                            @foreach($data['deductions_headers'] as $index => $ded)
                                                <td align="right" class="{{ $index == count($data['deductions_headers']) - 1 ? 'border-end' : '' }} text-danger">{{ number_format($data['rows']->sum(function($row) use ($ded) { return $row['dynamic_deductions'][$ded]; }), 2) }}</td>
                                            @endforeach
                                        @else
                                            <td class="border-end"></td>
                                        @endif

                                        <td align="right">{{ number_format($data['rows']->sum('total_deductions'), 2) }}</td>
                                        <td align="right">{{ number_format($data['rows']->sum('salary_part_a'), 2) }}</td>
                                        <td align="right">{{ number_format($data['rows']->sum('salary_part_b'), 2) }}</td>
                                        <td align="right">0.00</td>
                                        <td align="right" class="border-end">{{ number_format($data['rows']->sum('net_payment'), 2) }}</td>

                                        <td></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endforeach

                    @if(count($registerData) === 0)
                    <div class="card mb-4 text-center">
                        <div class="card-body">
                           <p class="text-muted m-0">No records found for the selected criteria.</p>
                        </div>
                    </div>
                    @endif
                </div>
                <x-footer />
            </div>
        </div>
    </div>
</div>
@endsection
