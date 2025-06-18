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
                            <div class="nav-align-top nav-tabs-shadow mb-4 mt-4">
                                <ul class="nav nav-tabs pt-2" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile" aria-selected="false" tabindex="-1">
                                            <strong> Approvels </strong>
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="true">
                                            <strong> Report </strong>
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content ">
                                    <div class="tab-pane fade" id="navs-pills-top-home" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12">

                                                

                                            </div>
                                            <div class="col-sm-12" id="reportView"></div>
                                        </div>
                                    </div>
                      
                                    <div class="tab-pane fade active show" id="navs-pills-top-profile" role="tabpanel">
                                        
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> WFH/WFS Approvals</h4>
                                        </div>
                                        
                                        <div class="card-datatable table-responsive">
                                            <table class="table table-striped table-bordered table-sm" id="wfs_wfh_pending" style="font-size: 12px">
                                                <thead>
                                                    <tr>
                                                        <th>Sl. No.</th>
                                                        <th><i class="ti ti-user ti-sm"></i></th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>signin date</th>
                                                        <th>signin time</th>
                                                        <th>signout date</th>
                                                        <th>signout time</th>
                                                        <th>break time</th>
                                                        <th>working hours</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($wfs_wfh_pending as $index => $attendance)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                @php
                                                                    $employee = $attendance->employee;
                                                                    $name = $employee->full_name ?? 'NA';
                                                                    $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper($word[0]))->join('');
                                                                    $initials = substr($initials, 0, 2);
                                                                @endphp

                                                                @if ($employee && $employee->profile_image)
                                                                    <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="{{ $name }}" width="40" height="40" class="rounded-circle">
                                                                @else
                                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                        {{ $initials }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>{{ $attendance->employee->full_name ?? 'N/A' }}</td>
                                                            <td>{{ $attendance->username ?? 'N/A' }}</td>
                                                            <td>{{ date('d-m-Y', strtotime($attendance->signin_date)) ?? 'N/A' }}</td>
                                                            <td> <span class="alert bg-label-success p-1"> {{ $attendance->signin_time ?? 'N/A' }} </span> </td>
                                                            <td>{{ date('d-m-Y', strtotime($attendance->signout_date)) ?? 'N/A' }}</td>
                                                            <td> <span class="alert bg-label-success p-1"> {{ $attendance->signout_time ?? 'N/A' }} </span> </td>
                                                            <td> <span class="alert bg-label-danger p-1"> {{ $attendance->break_time ?? 'N/A' }} </span> </td>
                                                            <td> <span class="alert bg-label-warning p-1"> {{ $attendance->working_hours ?? 'N/A' }} </span> </td>
                                                            <td><span class="alert bg-label-primary p-1"> {{ $attendance->status ?? 'N/A' }} </span></td>
                                                            <td>
                                                            <a href="{{ route('wfs-wfh-approve', $attendance->id) }}"  class="btn btn-sm btn-success p-1" onclick="return confirm('Are you sure you want to approve this attendance?')"> <i class="ti ti-check"></i> </a>
                                                            <a href="{{ route('wfs-wfh-reject', $attendance->id) }}"  class="btn btn-sm btn-danger p-1" onclick="return confirm('Are you sure you want to reject this attendance?')"> <i class="ti ti-x"></i> </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No records found.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>

                <!-- Footer -->
                <x-footer /> 
                <!-- / Footer -->
                 
                <div class="content-backdrop fade"></div>

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>

                <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>
@stop


@push('js')
<script>
    $(function(){

    });
    
    /* Get Incomplete Working Hours */
    function getIncompleteWorkingHoursReport() {
        var year = $('#years').val();
        var month = $('#months').val();
        var url = "{{ route('attendance.get-incomplete-working-hours-report') }}";
        $.ajax({
            type: "get",
            url: url,
            data: { year: year, month: month },
            dataType: "json",
            success: function (response) {
                $('#reportView').html(response.html);
                $('#incompleteWorkingHoursTable').DataTable();
            }
        });    
    }
</script>
@endpush