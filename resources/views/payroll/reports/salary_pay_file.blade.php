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
                        <span class="text-muted fw-light">Payroll / Reports /</span> Salary Pay File Report
                    </h4>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('payroll.reports.salary-pay-file') }}" class="row g-3 align-items-end">
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
                                <div class="col-md-2">
                                    <label class="form-label">Salary Structure</label>
                                    <select name="structure_id" class="form-select" required>
                                        <option value="" disabled {{ !$structureId ? 'selected' : '' }}>Select Structure</option>
                                        @foreach($structures as $struct)
                                        <option value="{{ $struct->id }}" {{ $structureId == $struct->id ? 'selected' : '' }}>{{ $struct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Salary Type</label>
                                    <select name="salary_type" class="form-select">
                                        {{-- <option value="both" {{ $salaryType == 'both' ? 'selected' : '' }}>Both (Part 1 + 2)</option> --}}
                                        <option value="part_a" {{ $salaryType == 'part_a' ? 'selected' : '' }}>Part - 1 Salary</option>
                                        <option value="part_b" {{ $salaryType == 'part_b' ? 'selected' : '' }}>Part - 2 Salary</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Generated Date</label>
                                    <input type="date" name="generated_date" class="form-control" value="{{ $generatedDate ? date('Y-m-d', strtotime($generatedDate)) : date('Y-m-d') }}">
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary d-block w-100">
                                        <i class="ti ti-search me-1"></i> Generate
                                    </button>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end gap-2">
                                    <button type="submit" name="export" value="excel" class="btn btn-success">
                                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(!$companyBank)
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Company Bank Configuration not found. Please <a href="{{ route('company-bank-configurations.index') }}" class="fw-bold">configure company bank details</a> first.
                    </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="datatables-basic table table-bordered table-striped border-top" style="white-space: nowrap;">
                                <thead class="table-light">
                                    <tr style="font-size: 0.8rem;">
                                        <th>Sl No</th>
                                        <th>Transaction Type</th>
                                        <th>Beneficiary Code</th>
                                        <th>Value Date</th>
                                        <th>Debit A/C Number</th>
                                        <th>Transaction Amount</th>
                                        <th>Beneficiary Name</th>
                                        <th>Beneficiary A/c No.</th>
                                        <th>IFSC Code</th>
                                        <th>Bene Email ID</th>
                                        <th>Bene Mobile No</th>
                                        <th>Customer Ref No.</th>
                                        <th>Payment Narration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payFileData as $index => $row)
                                    <tr style="font-size: 0.85rem;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row['transaction_type'] }}</td>
                                        <td></td> {{-- Beneficiary Code --}}
                                        <td>{{ $generatedDate }}</td>
                                        <td>{{ $companyBank->account_no ?? 'N/A' }}</td>
                                        <td align="right">{{ number_format($row['net_salary'], 2) }}</td>
                                        <td>{{ $row['full_name'] }}</td>
                                        <td>{{ $row['account_number'] }}</td>
                                        <td>{{ $row['ifsc'] }}</td>
                                        <td>{{ $row['personal_email'] }}</td>
                                        <td>{{ $row['phonenumber'] }}</td>
                                        <td>{{ $row['customer_ref_no'] }}</td>
                                        <td>SALARY</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="13" class="text-center py-4">No records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
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
