@extends('layouts.app')

@section('css')
<style>
    
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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Report /</span> {{ $meta_title }}</h4>

                    <div class="row">

                        <div class="col-sm-10">

                            <div class="card card-bg">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center"> 

                                        <div class="col-sm-6 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-body">
                                                    <!-- user avatar -->
                                                    <div class="user-avatar-section">
                                                        <div class="d-flex align-items-center flex-column">
                                                        <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ $current_user->profile_image ? asset('storage/' . $current_user->profile_image ) : '../../assets/img/avatars/15.png' }}" height="150" width="150" alt="User avatar">
                                                        <div class="user-info text-center">
                                                            <h4 class="mb-2">{{ $current_user->full_name ?? '' }}</h4>
                                                            <span class="badge bg-label-secondary mt-1">{{ $current_user->role ?? '' }}</span>
                                                        </div>
                                                        </div>
                                                    </div>
        
                                                    <div class="d-flex justify-content-around flex-wrap  pt-3 pb-4">
                                                        <div class="d-flex align-items-start mt-3 gap-2">
                                                            <a href="" class="btn btn-success"> <i class="ti ti-eye me-sm-1 me-0"></i> Profile </a>
                                                        </div>
        
                                                        <div class="d-flex align-items-start pt-3 gap-2">
                                                            <a href="" class="btn btn-primary"> <i class="ti ti-eye me-sm-1 me-0"></i> Work Summary </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-6 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Monthly Total Working Hours</h5>
                                                </div>
                                                <div class="card-body">
                                                    @php
                                                        $isDarkStyle = $isDarkStyle ?? false;
                                                    @endphp
                                                    
                                                    <x-charts.bar-chart 
                                                        id="workingHoursChart"
                                                        :labels="$labels"
                                                        :data="$average_hours"
                                                        :colors="[
                                                            'cardColor' => $isDarkStyle ? '#1E1E2D' : '#ffffff',
                                                            'headingColor' => $isDarkStyle ? '#ffffff' : '#000000',
                                                            'labelColor' => '#999',
                                                            'legendColor' => '#333',
                                                            'borderColor' => '#eee'
                                                        ]"
                                                        barColor="#28dac6"
                                                        :isRtl="false"
                                                        :maxY="300"
                                                        :stepY="50"
                                                        height="300px"
                                                    />

                                                    <!-- <canvas id="barChart" class="chartjs" data-height="100px"></canvas> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"> 

                                        <div class="col-sm-12 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-datatable">
                                                    <table class="table table-datatable table-hover table-striped" id="working-hours-report">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Month</th>
                                                                <th>Avg. Working hours</th>
                                                                <th>Total Working hours</th>
                                                                <th>No of Working Day</th>
                                                                <th>Leave</th>
                                                                <th>Year</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($monthly_report as $index => $row)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $row['month'] }}</td>
                                                                    <td>{{ $row['avg_hours'] }}</td>
                                                                    <td>{{ $row['total_hours'] }}</td>
                                                                    <td>{{ $row['working_days'] }}</td>
                                                                    <td>{{ $row['leaves'] }}</td>
                                                                    <td>{{ $row['year'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Current Work Analytics</h5>
                                                </div>
                                                <div class="card-body">

                                                @php
                                                    $donutLabels = array_keys($work_analysis);
                                                    $donutData = array_values($work_analysis);
                                                @endphp

                                                <x-charts.attendance-donut-chart
                                                        id="workAnalyticsChart"
                                                        :labels="$donutLabels"
                                                        :donutsData="$donutData"
                                                        :backgroundColors="['#2b9bf4', '#826bf8', '#3fd0bd', '#fee802', '#fee802', '#ea5455']"
                                                        height="360px"
                                                    />
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Current Attendance Analytics</h5>
                                                </div>
                                                <div class="card-body">

                                                    <x-charts.attendance-donut-chart
                                                        id="attendanceDonut"
                                                        :labels="['Completed', 'Half Days', 'Off', 'Custom', 'Holidays', 'Leaves']"
                                                        :donutsData="[
                                                            $attendance_analytics['completed_days'] ?? 0,
                                                            $attendance_analytics['incomplete_or_half_days'] ?? 0,
                                                            $attendance_analytics['off_days'] ?? 0,
                                                            $attendance_analytics['custom_days'] ?? 0,
                                                            $attendance_analytics['total_holidays'] ?? 0,
                                                            $attendance_analytics['total_leaves'] ?? 0
                                                        ]"
                                                        :backgroundColors="['#fee802', '#3fd0bd', '#826bf8', '#2b9bf4', '#f86624', '#ea5455']"
                                                        height="360px"
                                                    />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="row">

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">{{ $leave_stats['this_month_leaves'] ?? 0 }}</h5>
                                                                    <small>This Month Leave(s)</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">{{ $leave_stats['total_leaves_taken'] ?? 0 }}</h5>
                                                                    <small>Total Leave(s) Taken</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">{{ $leave_stats['total_leaves_allotted'] ?? 0 }}</h5>
                                                                    <small>Total Leave(s) Alloted</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">{{ $leave_stats['total_paid_leaves'] ?? 0 }}</h5>
                                                                    <small>Total Paid Leave(s)</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">{{ $leave_stats['past_year_leaves'] ?? 0 }}</h5>
                                                                    <small>Past Year Leave(s)</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">{{ $leave_stats['total_pending_leaves'] ?? 0 }}</h5>
                                                                    <small>Total Pending Leave(s)</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">
                                                                        @if ($leave_stats['category_wise_leaves'])
                                                                            @foreach ($leave_stats['category_wise_leaves'] as $key => $category_wise_leave)
                                                                               <span class="badge bg-label-info p-1 rounded">{{ $key }}: {{ $category_wise_leave }} </span> 
                                                                            @endforeach
                                                                        @endif
                                                                    </h5>
                                                                    <small>Leave(s) Category Wise</small>
                                                                </div>
                                                                <div class="">
                                                                    <span class="badge bg-label-success rounded-pill p-2">
                                                                        <i class="ti ti-bolt ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Yearly Work Analytics - {{ date('Y') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <x-charts.bar-chart 
                                                        id="yearlyHoursChart"
                                                        :labels="$labels"
                                                        :data="$average_hours"
                                                        :colors="[
                                                            'cardColor' => $isDarkStyle ? '#1E1E2D' : '#ffffff',
                                                            'headingColor' => $isDarkStyle ? '#ffffff' : '#000000',
                                                            'labelColor' => '#999',
                                                            'legendColor' => '#333',
                                                            'borderColor' => '#eee'
                                                        ]"
                                                        barColor="#28dac6"
                                                        :isRtl="false"
                                                        :maxY="300"
                                                        :stepY="50"
                                                        height="300px"
                                                    />

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="card card-bg mb-4">
                                <div class="card-header">
                                    <h5 class="card-title"> <i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <form action="{{ route('reports.user-overview') }}" method="GET"> 
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="user">Select User</label>
                                            <select name="user" id="user" class="form-control select2">
                                                <option value="">Select</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->user_id }}" {{ request('user') == $employee->user_id ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="year">Year</label>
                                            <select name="year" id="year" class="form-control select2">
                                                <option value="">Select</option>
                                                @for ($year = now()->year; $year >= 2014; $year--)
                                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <button type="submit" class="btn btn-primary">Find</button>
                                        </div>
                                    </form>
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
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
    $(function () {
        $('#working-hours-report').dataTable();
    });
</script>
@endpush