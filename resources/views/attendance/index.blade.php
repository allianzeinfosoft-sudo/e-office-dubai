@extends('layouts.app')

@section('css')

@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">

  <div class="layout-container">
    
    <x-menu /> <!-- Load the menu component here -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <x-header />

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">

              <div class="col-lg-7 mb-4">
                <!-- Attendance Marking Card -->
                <div class="card bg-primary text-white mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <small class="d-block mb-1 text-white"> {{ ucfirst(Auth::user()->username ?? 'N/A') }} </small>
                        </div>
                        <h4 class="card-title mb-1 text-white"> <i class="ti ti-clock ti-sm"></i> {{ $meta_title }}</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">

                        @if($attendance && $attendance->status =='mark-in')
                        <div class="alert alert-success" id="last-punch-time" role="alert">Last punch In Time: {{ date('H:i A', strtotime($attendance->signin_time)) }}</div>
                        @endif

                        @if($attendance && $attendance->status =='mark-out')
                        <div class="alert alert-warning" id="last-punch-time" role="alert"><strong>Next Punchin Tomorrow : </strong> Please Co-operate. </div>
                        @endif
                        
                        <div class="text-center d-grid gap-2 col-lg-12">
                          @if(!$attendance || $attendance->status !='mark-in')
                          <button type="button" id="mark-in-btn" class="btn rounded-pill btn-success waves-effect waves-light"><i class="ti ti-login ti-sm"></i> Mark-in </button>
                          @else
                          <button type="button" id="mark-out-btn" class="btn rounded-pill btn-danger waves-effect waves-light"> <i class="ti ti-logout ti-sm"></i> Mark-out</button>
                          @endif
                        </div>
                      </div>
                    </div>
                </div>

                <!-- Attendance summary report -->
                <div class="card mb-4">
                  <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                    <div class="card-title mb-0">
                      <h5 class="mb-0">No of Working Days</h5>
                      <small class="text-muted">Weekly Earnings Overview</small>
                    </div>
                    <div class="dropdown">
                      <button
                        class="btn p-0"
                        type="button"
                        id="earningReportsId"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId">
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                      </div>
                    </div>
                    <!-- </div> -->
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12 col-md-4 d-flex flex-column align-self-end">
                        <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
                          <h1 class="mb-0">{{ $days_of_worked ?? '0' }}</h1>
                          <div class="badge rounded bg-label-success">Days</div>
                        </div>
                        <small class="text-muted">You informed of this week compared to last week</small>
                      </div>
                      <div class="col-12 col-md-8">
                        <div id="weeklyEarningReports"></div>
                      </div>
                    </div>
                    <div class="border rounded p-3 mt-2">
                      <div class="row gap-4 gap-sm-0">
                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-primary p-1">
                              <i class="ti ti-calendar ti-sm"></i>
                            </div>
                            <h6 class="mb-0"> Working Days</h6>
                          </div>
                          <h4 class="my-2 pt-1"> {{ $days_of_worked ?? '0' }} </h4>
                          <div class="progress w-75" style="height: 4px">
                            <div class="progress-bar" role="progressbar" style="width: {{ $totalWorkingDays > 0 ? ($days_of_worked * 100) / $totalWorkingDays : 0 }}%" aria-valuenow="{{ $days_of_worked }}" aria-valuemin="0" aria-valuemax="{{ $totalWorkingDays }}"></div>
                          </div>
                        </div>

                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-info p-1"><i class="ti ti-hourglass ti-sm"></i></div>
                            <h6 class="mb-0"> Total Worked Hours</h6>
                          </div>
                          <h4 class="my-2 pt-1">{{ $totalWorkedHours ?? '0' }}</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-danger p-1">
                              <i class="ti ti-clock ti-sm"></i>
                            </div>
                            <h6 class="mb-0">Avg. Working Hours</h6>
                          </div>
                          <h4 class="my-2 pt-1">{{ $avgWorkedHours ?? '0' }}</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $avgProgressPercentage }}%" aria-valuenow="{{ $avgProgressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              
              <!-- Attendance Options -->
              <div class="col-lg-5 col-sm-6 mb-4">
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="row">
                      <div class="d-grid gap-2 col-lg-12">
                        <button class="btn rounded-pill btn-warning btn-lg waves-effect waves-light" type="button">Custom</button>
                        <button class="btn rounded-pill btn-primary btn-lg waves-effect waves-light" type="button">Emergency</button>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="card mb-4">
                  <div class="card-body pb-0">
                    <h5 class="card-title mb-0 mt-2">Total Hours</h5>
                  </div>
                  <div id="supportTracker"></div>
                </div>

              </div>
              <!--/ Attendance Options -->

            </div>
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <x-footer /> 
          <!-- / Footer -->

          <div class="content-backdrop fade"> </div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
@endsection


@section('js')
<script>
  $(function(){

    /* Mark in function */
    $('#mark-in-btn').on('click', function() {
      $.ajax({
          url: '{{ route('attendance.mark-in') }}',
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          contentType: 'application/json',
          data: JSON.stringify({}),
          success: function(data) {
              if (data.success) {
                  alert(data.message);
                  $('#last-punch-time').text(`Last punch In Time: ${data.data.signin_time}`);
              } else {
                  alert(data.message);
                  if (data.data.signin_time) {
                      $('#last-punch-time').text(`Last punch In Time: ${data.data.signin_time}`);
                  }
              }
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
          }
      });
    });

    /* Mark out function */
    $('#mark-out-btn').on('click', function() {
      $.ajax({
            url: '{{ route('attendance.mark-out') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            contentType: 'application/json',
            data: JSON.stringify({}),
            success: function(data) {
                if (data.success) {
                    alert(data.message);
                    $('#last-punch-out-time').text(`Last punch Out Time: ${data.data.signout_time}`);
                    $('#mark-out-btn').prop('disabled', true);
                } else {
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
    
    const categories = @json($categories);
    const seriesData = @json($seriesData);
    const weeklyEarningReportsEl = document.querySelector('#weeklyEarningReports');
    
    const weeklyEarningReportsConfig = {
      chart: {
        height: 202,
        parentHeightOffset: 0,
        type: 'bar',
        toolbar: { show: false }
      },
      plotOptions: {
        bar: {
          barHeight: '60%',
          columnWidth: '38%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4,
          distributed: true
        }
      },
      grid: {
        show: false,
        padding: {
          top: -30,
          bottom: 0,
          left: -10,
          right: -10
        }
      },
      colors: ['#28a745'], // Green for working days
      dataLabels: {
        enabled: false
      },
      series: [
        {
          name: 'Worked Hours',
          data: seriesData
        }
      ],
      legend: {
        show: false
      },
      xaxis: {
        categories: categories,
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
          style: {
            colors: '#6c757d',
            fontSize: '10px',
            fontFamily: 'Public Sans'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: '#6c757d',
            fontSize: '10px',
            fontFamily: 'Public Sans'
          }
        }
      },
      tooltip: {
        enabled: true,
        y: {
          formatter: (value) => `${value} hrs`
        }
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 199
            }
          }
        }
      ]
    };

    if (typeof weeklyEarningReportsEl !== undefined && weeklyEarningReportsEl !== null) {
      const weeklyEarningReports = new ApexCharts(weeklyEarningReportsEl, weeklyEarningReportsConfig);
      weeklyEarningReports.render();
    }
    
    // Support Tracker - Radial Bar Chart
    
  const todayProgressPercentage = @json($todayProgressPercentage);
  const todayWorkedHours = @json($todayWorkedHours);

  const supportTrackerEl = document.querySelector('#supportTracker'),
    supportTrackerOptions = {
      series: [todayProgressPercentage],
      labels: [`Total Worked Hrs (${todayWorkedHours})`],
      chart: {
        height: 360,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          offsetY: 10,
          startAngle: -140,
          endAngle: 130,
          hollow: {
            size: '65%'
          },
          track: {
            background: '#fff',
            strokeWidth: '100%'
          },
          dataLabels: {
            name: {
              offsetY: -20,
              color: '#a5a3ae',
              fontSize: '13px',
              fontWeight: '400',
              fontFamily: 'Public Sans'
            },
            value: {
              offsetY: 10,
              color: '#5d596c',
              fontSize: '38px',
              fontWeight: '600',
              fontFamily: 'Public Sans'
            }
          }
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          shadeIntensity: 0.5,
          gradientToColors: [config.colors.primary],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 0.6,
          stops: [30, 70, 100]
        }
      },
      stroke: {
        dashArray: 10
      },
      grid: {
        padding: {
          top: -20,
          bottom: 5
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 330
            }
          }
        },
        {
          breakpoint: 769,
          options: {
            chart: {
              height: 280
            }
          }
        }
      ]
    };
  if (typeof supportTrackerEl !== undefined && supportTrackerEl !== null) {
    const supportTracker = new ApexCharts(supportTrackerEl, supportTrackerOptions);
    supportTracker.render();
  }


  });



</script>
@stop