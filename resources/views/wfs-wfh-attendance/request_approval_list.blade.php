@extends('layouts.app')

@section('css')
<style>
    .nav-tabs { background-color: transparent !important; }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">    
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-12 card-bg">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> WFH/WOS Request Report</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('wfs-wfh-request-list') }}" method="GET">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">From Date</label>
                                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">To Date</label>
                                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Employee</label>
                                                <select name="emp_id" class="form-select select2">
                                                    <option value="">All Employees</option>
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->user_id }}" {{ request('emp_id') == $emp->user_id ? 'selected' : '' }}>
                                                            {{ $emp->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Type</label>
                                                <select name="request_type" class="form-select">
                                                    <option value="">All</option>
                                                    <option value="wfh" {{ request('request_type') == 'wfh' ? 'selected' : '' }}>WFH</option>
                                                    <option value="wos" {{ request('request_type') == 'wos' ? 'selected' : '' }}>WOS</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="card-datatable table-responsive">
                                    <table class="table table-striped table-bordered table-sm" id="wfs_wfh_requests" style="font-size: 12px">
                                        <thead>
                                            <tr>
                                                <th>Sl. No.</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Option</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requests as $index => $request)
                                                @php
                                                    $employee = $request->employee;
                                                    $name = $employee->full_name ?? 'NA';
                                                    $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper($word[0]))->join('');
                                                    $initials = substr($initials, 0, 2);
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($employee && $employee->profile_image)
                                                            <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="{{ $name }}" width="40" height="40" class="rounded-circle">
                                                        @else
                                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                {{ $initials }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $request->employee->full_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-label-{{ $request->request_type == 'wfh' ? 'primary' : 'danger' }}">
                                                            {{ strtoupper($request->request_type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ date('d-m-Y', strtotime($request->from_date)) }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($request->to_date)) }}</td>
                                                    <td>{{ $request->reason }}</td>
                                                    <td>
                                                        @if($request->status == 0)
                                                            <span class="badge bg-label-warning">Pending</span>
                                                        @elseif($request->status == 1)
                                                            <span class="badge bg-label-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-label-danger">Rejected</span>
                                                        @endif
                                                    <td>
                                                        <span class="badge bg-label-info">{{ ucfirst($request->attendance_option ?? 'personal') }}</span>
                                                    </td>
                                                    <td>
                                                        @if($request->status == 0)
                                                            <a href="{{ route('wfs-wfh-request-approve', $request->id) }}" class="btn btn-sm btn-success p-1" onclick="return confirm('Are you sure you want to approve this request?')">
                                                                <i class="ti ti-check"></i> Approve
                                                            </a>
                                                            <a href="{{ route('wfs-wfh-request-reject', $request->id) }}" class="btn btn-sm btn-danger p-1" onclick="return confirm('Are you sure you want to reject this request?')">
                                                                <i class="ti ti-x"></i> Reject
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">Action Taken</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-footer /> 
                <div class="content-backdrop fade"></div>
                <div class="layout-overlay layout-menu-toggle"></div>
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $(function(){
        $('#wfs_wfh_requests').DataTable({
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', title: 'WFH/WOS Request Report' },
                { extend: 'pdfHtml5', title: 'WFH/WOS Request Report', orientation: 'landscape', pageSize: 'A4' },
                { extend: 'print', title: 'WFH/WOS Request Report' }
            ],
        });
        $('.select2').select2();
    });
</script>
@endpush
