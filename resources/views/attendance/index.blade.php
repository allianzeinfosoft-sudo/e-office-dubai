@extends('layouts.app')

@section('css')
<style>
    .w-35 {
        width: 35% !important;
    }

    .w-45 {
        width: 45% !important;
    }

    .offcanvas-close{
        position: absolute;
        top: 0px;
        left: -32px;  /* Moves the button outside the offcanvas */
        z-index: 1055; /* Ensures it stays on top */
        padding: 28px 10px;
        border-radius: 0px;
    }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">

  <div class="layout-container {{ $background_class ?? 'bg-eoffice' }} ">
    
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
            <h4 class="fw-bold py-3 mb-4 text-muted "><span class="text-muted fw-light"></span>{{ $meta_title }}</h4>

            <div class="row">

                <!-- Statistics -->
                <div class="col-12 col-xl-12 col-lg-12">
                  <div class="row g-4 mb-4 justify-content-center">

                    <div class="col-sm-6 col-xl-4">
                      <div class="card card-bg">
                        <div class="card-body">
                          <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                              <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $days_of_worked ?? '0' }}</h4>                               
                              </div>
                              <span>No of Working Days</span>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                              <i class="ti ti-calendar ti-sm"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-6 col-xl-4">
                      <div class="card card-bg ">
                        <div class="card-body">
                          <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                              <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $totalWorkedHours ?? '0' }}</h4>
                              </div>
                              <span>Total Working Hours</span>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                              <i class="ti ti-hourglass-high  ti-sm"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-6 col-xl-4">
                      <div class="card card-bg ">
                        <div class="card-body">
                          <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                              <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $avgWorkedHours ?? '0' }}</h4>
                              </div>
                              <span>Avg. Working Hour(s)</span>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                              <i class="ti ti-info-circle ti-sm"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                <!--/ Statistics -->                
             
                <div class="col-12 col-xl-12 col-lg-12 ">
                  <div class="row g-4 mb-4 align-items-center">
                  
                    <!-- Markin module -->
                    <div class="col-12 col-xl-8 col-lg-8">
                      <div class="card card-sm">
                        <div class="card-header">
                            <h4 class="card-title mb-1"> <i class="ti ti-user ti-sm"></i> {{ ucfirst(Auth::user()->username ?? 'N/A') }} </h4>
                        </div>
                        
                        <div class="card-body">                     
                          <div class="row mb-4 g-4">

                            <div class="col-sm-6 col-xl-6">
                              <div class="card">
                                <div class="card-body">
                                  <div class="d-flex align-items-start justify-content-between">
                                    <div class="content-left">
                                      <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ date('d-m-Y') }}</h4>                               
                                      </div>
                                      <span>{{ date('l') }}</span>
                                    </div>
                                    <span class="badge bg-label-warning rounded p-2">
                                      <i class="ti ti-calendar ti-sm"></i>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-sm-6 col-xl-6">
                              <div class="card">
                                <div class="card-body">
                                  <div class="d-flex align-items-start justify-content-between">
                                    <div class="content-left">
                                      <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2"><span id="attendance_clock">00:00:00 </span> </h4>
                                      </div>
                                      <span>Time</span>
                                    </div>
                                    <span class="badge bg-label-warning rounded p-2">
                                      <i class="ti ti-clock  ti-sm"></i>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>

                          <div class="row g-4">
                            <div class="col-lg-12">
                              @if($attendance)
                                  @if(in_array($attendance->status, ['mark-in', 'custom', 'emergency']))
                                      <div class="badge bg-label-success p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                          Last punch In Time: {{ date('H:i A', strtotime($attendance->signin_time)) }}
                                      </div>
                                      <div class="text-center">
                                          <button type="button" id="mark-out-btn" class="btn p-3 btn-success w-100"> <i class="ti ti-arrow-big-left-lines ti-sm"></i> Mark-out </button>
                                      </div>
                                  @elseif($attendance->status === 'mark-out')
                                      <div class="badge bg-label-warning p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                          <strong>Next Punchin Tomorrow:</strong> Please Co-operate.
                                      </div>
                                  @endif

                              @elseif(!$attendance || !in_array($attendance->status, ['mark-in', 'custom', 'emergency']))
                                  @php
                                    $loginLimitTime = \Carbon\Carbon::parse(Auth::user()->employee->login_limited_time);
                                    $now = \Carbon\Carbon::now();
                                    $isLate = $now->gt($loginLimitTime);
                                    $todayName = $now->format('l'); // E.g., "Monday"
                                    $fixedWeekOffs = ['Saturday', 'Sunday'];
                                    $employeeWeekOffs = Auth::user()->employee->week_off_days ?? '';
                                    $customWeekOffs = array_map('trim', explode(',', $employeeWeekOffs));
                                    $allWeekOffs = array_unique(array_merge($fixedWeekOffs, $customWeekOffs));
                                    $isWeekOffToday = in_array($todayName, $allWeekOffs);
                                  @endphp


                                  @if(!empty($disableCustomMarkIn))
                                      <div class="badge bg-label-warning p-3 w-100 mb-3">
                                          You can mark in only between {{ $employee->workshift->shift_start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $employee->workshift->shift_start_time)->subMinutes(30)->format('h:i A') : '' }}
                                          and {{ $employee->workshift->shift_start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $employee->workshift->shift_start_time)->addMinutes(15)->format('h:i A') : '' }}.
                                      </div>
                                  @endif

                                  @if($isWeekOffToday)
                                    <div class="badge bg-label-warning p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                      <strong>Today ({{ $todayName }}) is your week off.</strong>
                                    </div>
                                  @endif

                                <div class="text-center">
                                  <button type="button" id="mark-in-btn" class="btn p-3 btn-primary w-100 {{ ($disableCustomMarkIn || $isLate || $isWeekOffToday) ? 'disabled' : '' }}"  {{ ($disableCustomMarkIn || $isLate || $isWeekOffToday) ? 'disabled' : '' }}>  Mark-in <i class="ti ti-arrow-big-right-lines ti-sm"></i> </button>
                                </div>

                              @else
                                <div class="text-center">
                                  <button type="button" id="mark-out-btn" class="btn p-3 btn-success w-100"> <i class="ti ti-arrow-big-left-lines ti-sm"></i> Mark-out </button>
                                </div>
                              @endif

                            </div>
  
                            <div class="text-center d-grid gap-2 col-lg-12">
                              
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>
                    <!--/ Markin module -->
                    
                    <!-- Custom module -->
                    <div class="col-12 col-xl-4  col-lg-4">
                      <div class="card card-bg pt-3">
                        <div class="row g-4 p-3">
                          <!-- custom -->
                          <div class="col-12 col-md-6 col-xl-12 col-lg-12 pt-2">
                            <button type="button" class="btn p-3 btn-info w-100" onclick="customModal()" >Custom <i class="mx-1 ti ti-arrow-big-right-lines ti-sm"></i></button>
                          </div>  
                          <!--/ custom -->

                          <!-- emergency -->
                          <div class="col-12 col-md-6 col-xl-12 col-lg-12"> 
                            <button type="button" class="btn p-3 btn-warning w-100" onclick="emergencyModal()">Emergency <i class="mx-1 ti ti-bolt ti-sm"></i></button>
                          </div>
                          <!--/ emergency -->

                          <div class="col-12 col-xl-12 col-lg-12 pb-4">
                            <div class="card badge bg-label-dark w-100 pt-3 pb-3">
                                <div class="d-flex align-items-start justify-content-between">
                                  <div class="content-left">
                                    <div class="d-flex align-items-center my-1">
                                      <h4 class="mb-0 me-2">{{ $todayWorkedHours ?? '0' }}</h4>
                                    </div>
                                    <span>Total Hours You Spent</span>
                                  </div>
                                  <div class="card-action-element">
                                    <ul class="list-inline mb-0">
                                      <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-reload"><i class="tf-icons ti ti-rotate-clockwise-2 scaleX-n1-rtl ti-sm"></i></a>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                    
                    <!-- Custom module -->

                  </div>
                </div>
              </div>  

           
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <x-footer /> 
          <!-- / Footer -->

          <div class="content-backdrop fade"> </div>

          <!-- Overlay -->
          <div class="layout-overlay layout-menu-toggle"></div>
      
          <!-- Drag Target Area To SlideIn Menu On Small Screens -->
          <div class="drag-target"></div>

        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

  </div>
  <!-- / Layout wrapper -->





<!-- Offcanvas for Custom Marking  -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="customMarkingOffcanvas" aria-labelledby="staticBackdropLabel">
  <div class="offcanvas-header bg-primary">
      <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> <i class="ti ti-hourglass float-start fs-3"></i>  Custom Marking </h5>
      <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i>  </button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
          <form id="customMarkingForm" action="{{ route('attendance.custom-mark-in') }}" method="post">
            @csrf
            <div class="col-12 mb-3">
              <label for="signin_date" class="form-label">Date</label>
              <input type="text" class="form-control" value="{{ date('Y-m-d') }}"  placeholder="Date" disabled readonly />
            </div>
  
            <div class="col-12 mb-3">
              <label for="signin_time" class="form-label">Time</label>
              <input type="time" id="signin_time" name="signin_time" class="form-control" value="{{ date('H:i', strtotime('now')) }}"  placeholder="Time" />
              <input type="hidden" id="signin_date" name="signin_date" class="form-control" value="{{ date('Y-m-d') }}"  placeholder="Time" />
            </div>
  
            <div class="col-12 mb-3">
              <label for="signin_late_note" class="form-label">Reason</label>
              <textarea id="signin_late_note" name="signin_late_note" class="form-control"  placeholder="Reason" rows="5"></textarea>
            </div>
          </form>
          <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close"> Close </button>
            <button type="submit" onclick="customMarking()"class="btn btn-primary"> Submit </button>
          </div>
    </div>
  </div>
  <div class="offcanvas-footer"></div>
</div>

<!-- Offcanvas for Emergency Marking  -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="emergencyMarkingOffcanvas" aria-labelledby="staticBackdropLabel">
  <div class="offcanvas-header bg-primary">
    <h5 class="offcanvas-title text-white" id="staticBackdropLabel">  <i class="ti ti-device-watch float-start fs-3"></i> Emergency Marking</h5>
    <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i>  </button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
      <form id="emergencyMarkingForm" action="{{ route('attendance.emergency-mark') }}" method="post">
        @csrf
            <div class="col-12 mb-3">
                <label for="emergency_signin_date" class="form-label">Date</label>
                <input type="text" id="emergency_signin_date" name="signin_date" class="form-control" value="{{ date('Y-m-d') }}" placeholder="Date" readonly />
            </div>

            <div class="col-12 mb-3">
              <label for="emergency_signin_late_note" class="form-label">Reason</label>
              <textarea id="emergency_signin_late_note" name="signin_late_note" class="form-control" placeholder="Reason" rows="5"></textarea>
            </div>
  
            <div class="col-12 mb-3">
              <label for="time_in_out" class="form-label">Time</label>
              <input type="time" id="time_in_out" name="time_in_out" class="form-control" value="{{ date('H:i') }}" placeholder="Time" />
            </div>
          </form>
        </div>
        <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
          <button type="button" onclick="emergencyMarkIn()" class="btn btn-success">Mark In</button>
          <button type="button" onclick="emergencyMarkOut()" class="btn btn-danger">Mark Out</button>
        </div>
  </div>
  <div class="offcanvas-footer"></div>
</div>


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
                
                toastr["success"](data.message);
                toastr.options = {
                  "closeButton": false,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-top-right",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }
                //  alert(data.message);
                $('#last-punch-time').text(`Last punch In Time: ${data.data.signin_time}`);
                window.location.reload();
              } else {
                toastr["success"](data.message);
                toastr.options = {
                  "closeButton": false,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-top-right",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }
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
                      toastr["success"](data.message);
                      toastr.options = {
                      "closeButton": false,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": false,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                    }
                    $('#last-punch-out-time').text(`Last punch Out Time: ${data.data.signout_time}`);
                    $('#mark-out-btn').prop('disabled', true);
                    window.location.reload();
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

  function customModal(){
    var offcanvasElement = $('#customMarkingOffcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
    //$('#modelCustom').modal('show');
  }

  function emergencyModal(){
    var offcanvasElement = $('#emergencyMarkingOffcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
    //$('#emergencyMarking').modal('show');
  }

  function customMarking() {
    const formData = {
        _token: $('input[name="_token"]').val(), // CSRF token
        signin_time: $('#signin_time').val(),
        signin_date: $('#signin_date').val(),
        signin_late_note: $('#signin_late_note').val()
    };

    $.ajax({
        type: "POST",
        url: $('#customMarkingForm').attr('action'),
        data: formData,
        dataType: "json",
        success: function (response) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            if (response.success) {
                toastr.success(response.message);
                $('#customMarkingForm')[0].reset(); // Clear form after success
                const offcanvasElement = document.getElementById('customMarkingOffcanvas');
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (offcanvas) offcanvas.hide();
            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON?.errors;
            if (errors) {
                let errorMessages = Object.values(errors).flat().join('\n');
                toastr.error('Error:\n' + errorMessages);
            } else {
                toastr.error('An error occurred. Please try again.');
            }
        }
    });
}

/* Emergency marking section js */

function emergencyMarkIn() {
    emergencyMark('mark-in');
}

function emergencyMarkOut() {
    emergencyMark('mark-out');
}

function emergencyMark(type) {
    const formData = {
        _token: $('input[name="_token"]').val(),
        signin_date: $('#emergency_signin_date').val(),
        signin_late_note: $('#emergency_signin_late_note').val(),
        time_in_out: $('#time_in_out').val(),
        type: type // 'mark-in' or 'mark-out'
    };

    $.ajax({
        type: 'POST',
        url: $('#emergencyMarkingForm').attr('action'),
        data: formData,
        dataType: 'json',
        success: function (response) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: '4000',
                extendedTimeOut: '1000',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };

            if (response.success) {
                toastr.success(response.message);
                $('#emergencyMarkingForm')[0].reset(); // Clear form after success
                //$('#emergencyMarking').modal('hide'); // Close modal after success

                var offcanvasElement = $('#emergencyMarkingOffcanvas');
                var offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
                offcanvas.hide();
                if(type == 'mark-in') {
                  setTimeout(() => {
                    window.location.reload();
                  }, 300);
                }else{
                  setTimeout(() => {
                    window.location.href = "{{ route('work-report.emerbency-work-report') }}";
                  }, 300);
                }
            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON?.errors;
            if (errors) {
                let errorMessages = Object.values(errors).flat().join('\n');
                toastr.error('Error:\n' + errorMessages);
            } else {
                toastr.error('An error occurred. Please try again.');
            }
        }
    });
}



</script>
@stop