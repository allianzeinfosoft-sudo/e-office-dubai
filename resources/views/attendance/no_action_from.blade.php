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
                                <div class="badge bg-label-danger p-3 w-100" id="last-punch-time" role="alert">
                                    <strong>{!! $error ?? '' !!}</strong> <br /> Please contact your supervisor.
                                </div>
                            </div>
  
                            <div class="text-center d-grid gap-2 col-lg-12">
                              
                                  <div class="text-center">
                                    <button type="button" id="mark-in-btn" class="btn p-3 btn-primary w-100 disabled" >  Mark-in <i class="ti ti-arrow-big-right-lines ti-sm"></i> </button>
                                  </div>

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
                            <button type="button" class="btn p-3 btn-info w-100 disabled" >Custom <i class="mx-1 ti ti-arrow-big-right-lines ti-sm"></i></button>
                          </div>  
                          <!--/ custom -->

                          <!-- emergency -->
                          <div class="col-12 col-md-6 col-xl-12 col-lg-12"> 
                            <button type="button" class="btn p-3 btn-warning w-100 disabled">Emergency <i class="mx-1 ti ti-bolt ti-sm"></i></button>
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
        signin_date: $('#signin_date').val(),
        signin_late_note: $('#signin_late_note').val(),
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
                $('#emergencyMarking').modal('hide'); // Close modal after success
                window.location.reload();
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