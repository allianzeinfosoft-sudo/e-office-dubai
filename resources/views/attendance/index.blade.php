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
              <div class="col-lg-6 mb-4">
                <div class="card bg-primary text-white">
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
                        <div class="alert alert-danger" id="last-punch-time" role="alert">Last punch Out Time: {{ date('H:i A', strtotime($attendance->signout_time)) }}. You don't have permission to mark-in again </div>
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
              </div>
              
              <!-- Sales Overview -->
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                  <div class="card-body">

                    <div class="card-icon mb-3">
                      <span class="badge bg-label-success rounded-pill p-2">
                        <i class="ti ti-settings ti-sm"></i>
                      </span>
                    </div>
                    
                    <div class="row">
                      <div class="d-grid gap-2 col-lg-12">
                        <button class="btn rounded-pill btn-warning btn-lg waves-effect waves-light" type="button">Custom</button>
                        <button class="btn rounded-pill btn-primary btn-lg waves-effect waves-light" type="button">Emergency</button>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <!--/ Sales Overview -->

              <!-- Revenue Generated -->
              <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                  <div class="card-body pb-0">
                    <div class="card-icon">
                      <span class="badge bg-label-warning rounded-pill p-2">
                        <i class="ti ti-hourglass ti-sm"></i>
                      </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">5.30</h5>
                    <small>Total Hours you Spent </small>
                  </div>
                  <div id="revenueGenerated"></div>
                </div>
              </div>
              <!--/ Revenue Generated -->

              <!-- Earning Reports -->
              <div class="col-lg-6 mb-4">
                <div class="card h-100">
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
                          <h1 class="mb-0">10</h1>
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
                            <h6 class="mb-0">Working Days</h6>
                          </div>
                          <h4 class="my-2 pt-1">10</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>

                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-info p-1"><i class="ti ti-hourglass ti-sm"></i></div>
                            <h6 class="mb-0">Working Hours</h6>
                          </div>
                          <h4 class="my-2 pt-1">38.25</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-danger p-1">
                              <i class="ti ti-brand-paypal ti-sm"></i>
                            </div>
                            <h6 class="mb-0">Avg. Working Hours</h6>
                          </div>
                          <h4 class="my-2 pt-1">$74.19</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Earning Reports -->

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
    
  });
</script>
@stop