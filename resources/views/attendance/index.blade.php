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
                                <h4 class="mb-0 me-2">{{ round((float)$totalWorkedHours,2) ?? '0' }}</h4>
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
                            <h4 class="card-title mb-1"> <i class="ti ti-user ti-sm"></i> {{ ucfirst(Auth::user()->employee?->full_name ?? 'N/A') }} </h4>
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

                              @php
                                $loginLimitTime   = \Carbon\Carbon::parse(Auth::user()->employee->login_limited_time);
                                $now              = \Carbon\Carbon::now();
                                $isLate           = ($shiftType == 'fullday') ? false : $now->gt($loginLimitTime);
                                $todayName        = $now->format('l'); // E.g., "Monday"
                                $fixedWeekOffs    = ['Saturday', 'Sunday'];
                                $employeeWeekOffs = Auth::user()->employee->week_off_days ?? '';
                                $customWeekOffs   = array_map('trim', explode(',', $employeeWeekOffs));
                                $allWeekOffs      = array_unique(array_merge($fixedWeekOffs, $customWeekOffs));
                                $isWeekOffToday   = in_array($todayName, $allWeekOffs);
                              @endphp

                              @if(isset($attendance) || isset($attendance_current))
                              @if($shiftType == 'night')
                              @if($attendance_current?->signin_date == date('Y-m-d') && in_array($attendance_current?->status, ['mark-in', 'custom', 'emergency']))
                                      <div class="badge bg-label-success p-3 w-100 mb-3 text-dark" id="last-punch-time" role="alert">
                                          Last Punch In Time: {{ date('d-m-Y', strtotime($attendance_current?->signin_date)) }} {{ date('h:i A', strtotime($attendance_current?->signin_time)) }}
                                          <input type="hidden" name="attendance_id" id="attendance_id" value="{{ $attendance_current?->id }}" />
                                      </div>
                                      <div class="text-center">
                                          <button type="button" id="mark-out-btn" class="btn p-3 btn-success w-100">
                                              <i class="ti ti-arrow-big-left-lines ti-sm"></i> Mark-out
                                          </button>
                                      </div>
                                    @elseif($attendance_current?->status === 'mark-out')
                                      @php
                                          $nextLoginTime = \Carbon\Carbon::createFromFormat('H:i:s', $employee->workshift->shift_start_time)->subMinutes(30)->format('h:i A');
                                          $lastWorkingDate = \Carbon\Carbon::now()->subDay()->toDateString();
                                          $prevAttendance = \App\Models\Attendance::where('username', Auth::user()->username)->where('signin_date', $lastWorkingDate)->first();
                                      @endphp

                                      @if($isWeekOffToday || $isHolidayToday)
                                          @if(!$prevAttendance || !$prevAttendance->signout_time)
                                              <div class="badge bg-label-danger p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                                  <strong>Missed Mark-out Detected:</strong> Please contact admin to regularize yesterday's attendance.
                                              </div>
                                          @else
                                              <div class="badge bg-label-warning p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                                  <strong>Next Login Time:</strong> {{ $nextLoginTime }} tomorrow.
                                              </div>
                                          @endif
                                      @else
                                          <div class="badge bg-label-warning p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                              <strong>Next Login Time:</strong> {{ $nextLoginTime }} tomorrow.
                                          </div>
                                      @endif

                                    @elseif($attendance?->signin_date == date('Y-m-d', strtotime('-1 day')) && in_array($attendance?->status, ['mark-in', 'custom', 'emergency']))

                                      <div class="badge bg-label-success p-3 w-100 mb-3 text-dark" id="last-punch-time" role="alert">
                                          Last Punch In Time: {{ date('d-m-Y', strtotime($attendance?->signin_date)) }} {{ date('h:i A', strtotime($attendance?->signin_time)) }}
                                          <input type="hidden" name="attendance_id" id="attendance_id" value="{{ $attendance?->id }}" />
                                      </div>

                                      <div class="text-center">
                                          <button type="button" id="mark-out-btn" class="btn p-3 btn-success w-100">
                                              <i class="ti ti-arrow-big-left-lines ti-sm"></i> Mark-out
                                          </button>
                                      </div>

                                    @else
                                      <div class="text-center">
                                          <button type="button" id="mark-in-btn" class="btn p-3 btn-primary w-100 {{ ($disableCustomMarkIn || $isWeekOffToday) ? 'disabled' : '' }}"
                                                  {{ ($disableCustomMarkIn || $isWeekOffToday) ? 'disabled' : '' }}>
                                              Mark-in <i class="ti ti-arrow-big-right-lines ti-sm"></i>
                                          </button>
                                      </div>
                                    @endif

                                @else
                                  {{-- day shift --}}
                                  @if(isset($attendance))
                                    @if(in_array($attendance->status, ['mark-in', 'custom', 'emergency']))
                                        <div class="badge bg-label-success p-3 w-100 mb-3 text-dark" id="last-punch-time" role="alert">
                                            Last Punch In Time: {{date('d-m-Y', strtotime($attendance->signin_date))}}  {{ date('H:i A', strtotime($attendance->signin_time)) }}
                                            <input type="hidden" name="attendance_id" id="attendance_id" value="{{ $attendance?->id }}" />
                                        </div>
                                        <div class="text-center">
                                            <button type="button" id="mark-out-btn" class="btn p-3 btn-success w-100"> <i class="ti ti-arrow-big-left-lines ti-sm"></i> Mark-out </button>
                                        </div>
                                    @elseif($attendance->status === 'mark-out')
                                        <div class="badge bg-label-warning p-3 w-100 mb-3" id="last-punch-time" role="alert">
                                            <strong>Next Punchin Tomorrow:</strong> Please Co-operate.
                                        </div>
                                    @endif

                                  @else
                                    <div class="text-center">
                                        <button type="button" id="mark-in-btn" class="btn p-3 btn-primary w-100 {{ ($disableCustomMarkIn || $isWeekOffToday) ? 'disabled' : '' }}"
                                                {{ ($disableCustomMarkIn || $isWeekOffToday) ? 'disabled' : '' }}>
                                            Mark-in <i class="ti ti-arrow-big-right-lines ti-sm"></i>
                                        </button>
                                    </div>
                                  @endif

                                @endif

                              @elseif(!isset($attendance) || !$attendance || !in_array($attendance->status, ['mark-in', 'custom', 'emergency']))

                                  @if($disableCustomMarkIn || $isLate || $isWeekOffToday)
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
                          <hr style ="margin: 1rem 0;" />
                          <!-- work from home -->
                            <div class="col-6 col-md-6 col-xl-6 col-lg-6">
                              <button type="button" class="btn btn-primary w-100" onclick="wfh_attendance()">Work From Home</button>
                            </div>

                            <div class="col-6 col-md-6 col-xl-6 col-lg-6">
                              <button type="button" class="btn btn-danger w-100" onclick="wos_attendance()">Work On Site</button>
                            </div>
                          <!--/ work from home -->

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
              <input type="time" id="signin_time" name="signin_time" class="form-control" value="{{ \Carbon\Carbon::now('Asia/Kolkata')->format('H:i') }}"  placeholder="Time" />
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
              <input type="time" id="time_in_out" name="time_in_out" class="form-control" value="{{ \Carbon\Carbon::now('Asia/Kolkata')->format('H:i') }}" placeholder="Time" />
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

<!-- work from home -->
 <div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="wfhOffcanvas" aria-labelledby="staticBackdropLabel">
  <div class="offcanvas-header bg-primary">
      <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> <i class="ti ti-hourglass float-start fs-3"></i>  WFH Attendance & Report </h5>
      <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i>  </button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
          <x-work-from-home-attendance-report type="wfh" />
    </div>
  </div>
  <div class="offcanvas-footer"></div>
</div>


<!-- work from site -->

<div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="wosOffcanvas" aria-labelledby="staticBackdropLabel">
  <div class="offcanvas-header bg-primary">
      <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> <i class="ti ti-hourglass float-start fs-3"></i>  Work On Site & Report </h5>
      <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i>  </button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
          <x-work-from-home-attendance-report type="wfs" />
    </div>
  </div>
  <div class="offcanvas-footer"></div>
</div>


{{-- marks as read --}}
<div class="modal fade" id="announcementModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gray text-white">
        <h5 class="modal-title" style="color: white">Important Announcement</h5>
      </div>
      <div class="modal-body">
        <h6 id="announcement-title"></h6>
        <p id="announcement-message"></p>
         <div id="announcement-image" class="text-center mt-3"></div>
        <input type="hidden" id="announcement-id" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="mark-as-read-btn">Mark as Read</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('js')
<script>

  $(function(){
    /* Mark in function */
    $('#mark-in-btn').on('click', function(e) {
      e.preventDefault(); // 🔐 stop form from submitting
      var $btn = $(this);

      // Prevent double click
      if ($btn.prop('disabled')) return;

      // Disable the button and show loading text
      $btn.prop('disabled', true).text('Loading..');

      $.ajax({
          url: "{{ route('attendance.mark-in') }}",
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
              // Re-enable the button if there's an error
              $btn.prop('disabled', false).text('Mark In');
          }
        });
    });

    /* Mark out function */
    $('#mark-out-btn').on('click', function() {


      var attendanceId = $('#attendance_id').val();
      var $btn = $(this);
      // Prevent double click
      if ($btn.prop('disabled')) return;
      // Disable button and show loading text
      $btn.prop('disabled', true).text('Loading..');

       if (!confirm("Are you sure you want to mark out?")) {

            $btn.prop('disabled', false).text('Mark Out');
            return;
        }
      check_announcement(function (canProceed) {

        if (canProceed) {
                $.ajax({
                    url: "{{ route('attendance.mark-out') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({'attendanceId' : attendanceId }),
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
                            // Optional: re-enable button and restore text if you don't reload
                            $btn.prop('disabled', false).text('Mark Out');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Re-enable on error
                        $btn.prop('disabled', false).text('Mark Out');
                    }
                });

        }
      });

    });


    $('.card-reload').on('click', function (e) {
      window.location.reload();
    });

  });


function check_announcement(callback) {
    $.ajax({
        url: '/check-announcement',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (res) {
             if (res.found && res.announcements && res.announcements.length > 0) {
                console.log("Unread announcements found.");
                handleMultipleAnnouncements(res.announcements, 0, function () {
                    console.log("All announcements handled.");
                    callback(true);
                });
            } else {
                console.log("No unread announcements.");
                callback(true);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error checking announcement:', error);
            $btn.prop('disabled', false).text('Mark Out');
        }
    });
}

function handleMultipleAnnouncements(announcements, index, doneCallback) {
    if (index >= announcements.length) {
        console.log("No more announcements to show.");
        doneCallback(); // All done, proceed
        return;
    }

    const current = announcements[index];

    $('#announcement-title').text(current.name_announcement);
    $('#announcement-message').html(current.description);

    if (current.picture) {
        const imagePath = `/storage/${current.picture}`;
        $('#announcement-image').html(`<img src="${imagePath}" alt="Announcement Image" class="img-fluid rounded">`);
    } else {
        $('#announcement-image').empty(); // Clear if no image
    }

    $('#announcement-id').val(current.id);
    $('#announcementModal').modal('show');

    $('#mark-as-read-btn').off('click').on('click', function () {
        markAsRead(current.id).then(() => {
            $('#announcementModal').modal('hide');

            // Wait for modal to hide before next
            $('#announcementModal').on('hidden.bs.modal', function () {
                $('#announcementModal').off('hidden.bs.modal');
                handleMultipleAnnouncements(announcements, index + 1, doneCallback);
            });
        });
    });
}



function markAsRead(announcementId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/mark-announcement-read',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: { announcement_id: announcementId },
            success: function (res) {
                if (res.status === 'success') resolve();
                else reject('Failed to mark as read');
            },
            error: function () {
                reject('Error marking as read');
            }
        });
    });
}


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


function wfh_attendance(){
    var offcanvasElement = $('#wfhOffcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
    const workType = 'wfh';
    //$('#modelCustom').modal('show');
    $('#' + workType + '_signin_date').flatpickr({
        monthSelectorType: 'static',
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'd-m-Y'
    });
}

function wos_attendance(){
    var offcanvasElement = $('#wosOffcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
    const workType = 'wfs';
    //$('#modelCustom').modal('show');
    $('#' + workType + '_signin_date').flatpickr({
        monthSelectorType: 'static',
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'd-m-Y'
    });
}



</script>
@stop
