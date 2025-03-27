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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>{{ $meta_title }}</h4>
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

                        @if($attendance)
                            @if(in_array($attendance->status, ['mark-in', 'custom', 'emergency']))
                                <div class="alert alert-success" id="last-punch-time" role="alert">
                                    Last punch In Time: {{ date('H:i A', strtotime($attendance->signin_time)) }}
                                </div>
                            @elseif($attendance->status === 'mark-out')
                                <div class="alert alert-warning" id="last-punch-time" role="alert">
                                    <strong>Next Punchin Tomorrow:</strong> Please Co-operate.
                                </div>
                            @endif
                        @endif

                        <div class="text-center d-grid gap-2 col-lg-12">
                            @if(!$attendance || !in_array($attendance->status, ['mark-in', 'custom', 'emergency']))
                                <button type="button" id="mark-in-btn" class="btn rounded-pill btn-success waves-effect waves-light">
                                    <i class="ti ti-login ti-sm"></i> Mark-in
                                </button>
                            @else
                                <button type="button" id="mark-out-btn" class="btn rounded-pill btn-danger waves-effect waves-light">
                                    <i class="ti ti-logout ti-sm"></i> Mark-out
                                </button>
                            @endif
                        </div>

                      </div>
                    </div>
                </div>

              </div>

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

</script>
@stop