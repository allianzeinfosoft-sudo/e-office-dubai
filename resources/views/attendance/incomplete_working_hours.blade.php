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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Attendance /</span> {{ $meta_title }}</h4>

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

                                                <form id="fullDayAttendanceForm" action="{{ route('attendance.full-day-attendance-entry') }}" method="post">
                                                    <div class="row">
                                                        @csrf
                                                        <div class="col-5 mb-3">
                                                            <label for="years" class="form-label">Years</label>
                                                            <select class="form-control select2" name="years" id="years">
                                                                <option value=""></option>
                                                                @if($years->isNotEmpty())
                                                                    @foreach($years as $year)
                                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                
                                                        <div class="col-5 mb-3">
                                                            <label for="months" class="form-label">Month</label>
                                                            <select class="form-control select2" name="months" id="months">
                                                                <option value=""></option>
                                                                @if($months->isNotEmpty())
                                                                    @foreach($months as $month)
                                                                        <option value="{{ $month->month }}">{{ $month->month_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-2 mt-4 mb-3 gap-2">
                                                            <button type="button" onclick="getIncompleteWorkingHoursReport()"class="btn btn-primary"> Search </button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="col-sm-12" id="reportView"></div>
                                        </div>
                                    </div>
                      
                                    <div class="tab-pane fade active show" id="navs-pills-top-profile" role="tabpanel">
                                        
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> Incomplete Working Hours Approvals</h4>
                                        </div>
                                        
                                        <div class="card-datatable table-responsive">
                                            <table class="table table-sm table-striped table-bordered" id="incompleteWorkingHoursTable">
                                                <thead>
                                                    <tr>
                                                        <th>Sl. No.</th>
                                                        <th><i class="ti ti-user ti-sm"></i></th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Working Hours</th>
                                                        <th>Sign-in Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($pending_approvels as $index => $attendance)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                @php
                                                                    $name = $attendance->employee->full_name ?? 'N/A';
                                                                    $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper($word[0]))->join('');
                                                                    $initials = substr($initials, 0, 2); 
                                                                @endphp
                                                                @if($attendance->employee->profile_image)
                                                                    <img src="{{ asset('storage/' . $attendance->employee->profile_image) }}" alt="Profile" width="40" height="40" class="rounded-circle">
                                                                @else
                                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                    {{ $initials }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>{{ $attendance->employee->full_name ?? 'N/A' }}</td>
                                                            <td>{{ $attendance->username ?? 'N/A' }}</td>
                                                            <td>
                                                                @if($attendance->signin_time && $attendance->signout_time)
                                                                    @php
                                                                        $signIn = \Carbon\Carbon::parse($attendance->signin_time);
                                                                        $signOut = \Carbon\Carbon::parse($attendance->signout_time);
                                                                        $totalSeconds = $signOut->diffInSeconds($signIn);

                                                                        $breakSeconds = 3600; // Subtract 1 hour break
                                                                        $workedSeconds = max(0, $totalSeconds - $breakSeconds);

                                                                        $hours = floor($workedSeconds / 3600);
                                                                        $minutes = floor(($workedSeconds % 3600) / 60);
                                                                        $seconds = $workedSeconds % 60;
                                                                    @endphp
                                                                        <span class="alert bg-label-danger"> {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }} </span> 
                                                                @else
                                                                    Incomplete
                                                                @endif
                                                            </td>
                                                            <td><span class="alert bg-label-primary"> {{ \Carbon\Carbon::parse($attendance->signin_date)->format('d-m-Y') }} </span></td>
                                                            <td>
                                                            <a href="{{ route('attendance.incomplete.approve', $attendance->id) }}"  class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this attendance?')"> <i class="ti ti-check"></i> Accept </a>
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