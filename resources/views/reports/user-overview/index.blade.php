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

                        <div class="col-sm-9">

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
                                                    <canvas id="barChart" class="chartjs" data-height="100px"></canvas>
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
                                                    <canvas id="doughnutChart" class="chartjs mb-4" data-height="350"></canvas>
                                                    <ul class="doughnut-legend d-flex justify-content-around ps-0 mb-2 pt-1">
                                                        
                                                        <li class="ct-series-0 d-flex flex-column">
                                                            <p class="mb-0 fw-bold">Outstanding</p>
                                                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                                                style="background-color: #836AF9 ; width: 35px; height: 6px"></span>
                                                        </li>
                                                        
                                                        <li class="ct-series-1 d-flex flex-column">
                                                            <p class="mb-0 fw-bold">Very Good</p>
                                                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                                                style="background-color: #ffe800; width: 35px; height: 6px"></span>
                                                        </li>

                                                        <li class="ct-series-2 d-flex flex-column">
                                                            <p class="mb-0 fw-bold">Good</p>
                                                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                                                style="background-color: #28dac6; width: 35px; height: 6px"></span>
                                                        </li>

                                                        <li class="ct-series-3 d-flex flex-column">
                                                            <p class="mb-0 fw-bold">Above Average</p>
                                                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                                                style="background-color: #FF8132; width: 35px; height: 6px"></span>
                                                        </li>

                                                        <li class="ct-series-4 d-flex flex-column">
                                                            <p class="mb-0 fw-bold">Average</p>
                                                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                                                style="background-color: #FDAC34; width: 35px; height: 6px"></span>
                                                        </li>

                                                        <li class="ct-series-5 d-flex flex-column">
                                                            <p class="mb-0 fw-bold">Poor</p>
                                                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                                                style="background-color: #299AFF; width: 35px; height: 6px"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Current Attendance Analytics</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div id="donutChart"></div>
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
                                                                    <h5 class="mb-0 me-2">0</h5>
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
                                                                    <h5 class="mb-0 me-2">0</h5>
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
                                                                    <h5 class="mb-0 me-2">0</h5>
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
                                                                    <h5 class="mb-0 me-2">0</h5>
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

                                                <div class="col-sm-4 mb-3">
                                                    <div class="card bg-white">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="mb-0">
                                                                    <h5 class="mb-0 me-2">0</h5>
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
                                                                    <h5 class="mb-0 me-2">0</h5>
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
                                                                    <h5 class="mb-0 me-2">0</h5>
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

                                            </div>
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <div class="card bg-white">
                                                <div class="card-body">
                                                    <canvas id="barChart" class="chartjs" data-height="300"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="card card-bg mb-4">
                                <div class="card-header">
                                    <h5 class="card-title"> <i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form action="" method="post">
                                                <div class="form-group mb-3">
                                                    <label for="user">Select User</label>
                                                    <select name="user" id="user" class="form-control select2">
                                                        <option value=""></option>
                                                        @if($employees->isnotempty())
                                                            @foreach($employees as $employee)
                                                                <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="year">Year</label>
                                                    <select name="year" id="year" class="form-control select2">
                                                    <option value=""></option>
                                                    @for ($year = now()->year; $year >= 2014; $year--)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endfor
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <button type="button" class="btn btn-primary"> Find </button>
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
<script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<!-- <script src="{{ asset('assets/js/charts-chartjs.js') }}"></script> -->
<script src="{{ asset('assets/js/charts-apex.js') }}"></script>
<script>
    $(function () {
        $('#working-hours-report').dataTable();
         // Color Variables
        const purpleColor = '#836AF9',
            yellowColor = '#ffe800',
            cyanColor = '#28dac6',
            orangeColor = '#FF8132',
            orangeLightColor = '#FDAC34',
            oceanBlueColor = '#299AFF',
            greyColor = '#4F5D70',
            greyLightColor = '#EDF1F4',
            blueColor = '#2B9AFF',
            blueLightColor = '#84D0FF';

        let cardColor, headingColor, labelColor, borderColor, legendColor;

        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            headingColor = config.colors_dark.headingColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            borderColor = config.colors.borderColor;
        }

        // Set height according to their data-height
        // --------------------------------------------------------------------
        const chartList = document.querySelectorAll('.chartjs');
        chartList.forEach(function (chartListItem) {
            chartListItem.height = chartListItem.dataset.height;
        });

        const barChart = document.getElementById('barChart');
        const chartLabels = {!! json_encode($labels) !!};
        const chartData = {!! json_encode($average_hours) !!};

        if (barChart) {
            const barChartVar = new Chart(barChart, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                {
                    data: chartData,
                    backgroundColor: cyanColor,
                    borderColor: 'transparent',
                    maxBarThickness: 15,
                    borderRadius: {
                    topRight: 15,
                    topLeft: 15
                    }
                }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                duration: 500
                },
                plugins: {
                tooltip: {
                    rtl: isRtl,
                    backgroundColor: cardColor,
                    titleColor: headingColor,
                    bodyColor: legendColor,
                    borderWidth: 1,
                    borderColor: borderColor
                },
                legend: {
                    display: false
                }
                },
                scales: {
                x: {
                    grid: {
                    color: borderColor,
                    drawBorder: false,
                    borderColor: borderColor
                    },
                    ticks: {
                    color: labelColor
                    }
                },
                y: {
                    min: 0,
                    max: 300,
                    grid: {
                    color: borderColor,
                    drawBorder: false,
                    borderColor: borderColor
                    },
                    ticks: {
                    stepSize: 50,
                    color: labelColor
                    }
                }
                }
            }
            });
        }


         // Doughnut Chart
        // --------------------------------------------------------------------

        const doughnutChart = document.getElementById('doughnutChart');

        const workAnalysisData = @json($work_analysis);
        const doughnutLabels = Object.keys(workAnalysisData);
        const doughnutData = Object.values(workAnalysisData);

        if (doughnutChart) {
            const doughnutChartVar = new Chart(doughnutChart, {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [
                {
                    data: doughnutData,
                    backgroundColor: [cyanColor, orangeLightColor, config.colors.primary],
                    borderWidth: 0,
                    pointStyle: 'rectRounded'
                }
                ]
            },
            options: {
                responsive: true,
                animation: {
                duration: 500
                },
                cutout: '68%',
                plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                    label: function (context) {
                        const label = context.labels || '',
                        value = context.parsed;
                        const output = ' ' + label + ' : ' + value + ' %';
                        return output;
                    }
                    },
                    // Updated default tooltip UI
                    rtl: isRtl,
                    backgroundColor: cardColor,
                    titleColor: headingColor,
                    bodyColor: legendColor,
                    borderWidth: 1,
                    borderColor: borderColor
                }
                }
            }
            });
        }

        /* ===================== */

        

    });
</script>
@endpush