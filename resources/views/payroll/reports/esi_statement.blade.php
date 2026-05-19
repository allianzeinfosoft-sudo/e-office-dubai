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
                        <span class="text-muted fw-light">Payroll / Reports /</span> ESI Statement
                    </h4>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('payroll.reports.esi-statement') }}" class="row g-3 align-items-end">
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
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary d-block w-100">
                                        <i class="ti ti-search me-1"></i> Generate
                                    </button>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end gap-2">
                                    <button type="submit" name="export" value="pdf" class="btn btn-label-secondary" formaction="{{ route('payroll.reports.esi-statement.export-pdf') }}">
                                        <i class="ti ti-file-pdf me-1"></i> PDF
                                    </button>
                                    <button type="submit" name="export" value="excel" class="btn btn-success" formaction="{{ route('payroll.reports.esi-statement.export') }}">
                                        <i class="ti ti-file-spreadsheet me-1"></i> Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0">ESI Statement - {{ date("F", mktime(0, 0, 0, $month, 10)) }} {{ $year }}</h5>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="table table-bordered table-striped border-top">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th>Emp. Code</th>
                                        <th>Name</th>
                                        <th class="text-end">ESI Salary</th>
                                        <th class="text-end">Employee Contri.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($esiData as $row)
                                    <tr>
                                        <td>{{ $row['sl_no'] }}</td>
                                        <td>{{ $row['emp_code'] }}</td>
                                        <td>{{ $row['name'] }}</td>
                                        <td align="right">{{ number_format($row['esi_salary'], 2) }}</td>
                                        <td align="right">{{ number_format($row['employee_contri'], 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No records found for the selected period.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if(count($esiData) > 0)
                                @php
                                    $totalEsiSalary      = $esiData->sum('esi_salary');
                                    $totalEmployeeContri = $esiData->sum('employee_contri');
                                    $employerContriCalc  = round($totalEsiSalary * ($esiEmployerPercent / 100));
                                    $grandTotal          = $employerContriCalc + $totalEmployeeContri;
                                @endphp
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td align="right">{{ number_format($totalEsiSalary, 2) }}</td>
                                        <td align="right">{{ number_format($totalEmployeeContri, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end">Employer Contribution ({{ $esiEmployerPercent }}%):</td>
                                        <td align="right">{{ number_format($employerContriCalc, 2) }}</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td colspan="4" class="text-end">Grand Total:</td>
                                        <td align="right">{{ number_format($grandTotal, 2) }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <x-footer />
            </div>
        </div>
    </div>
</div>
@endsection
