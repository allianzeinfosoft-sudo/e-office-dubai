@extends('layouts.app')
@section('content')
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
      <!-- Menu -->
      <x-menu /> <!-- Load the menu component here -->

      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <x-header />
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
         <div class="container-xxl flex-grow-1 container-p-y">
          {{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">DataTables /</span> Basic</h4> --}}

          <!-- DataTable with Buttons -->
          <div class="card">
            <div class="card-datatable table-responsive pt-0">
              <table class="datatables-basic table" id="datatables-workshift">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Shift ID</th>
                    <th>Department</th>
                    <th>Shift Start Time</th>
                    <th>Shift End Time</th>
                    <th>Min Break Time</th>
                    <th>Max Break Time </th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>

          <!-- Modal to add new record -->
          <div class="offcanvas offcanvas-end" id="add-new-shift">
            <div class="offcanvas-header border-bottom">
              <h5 class="offcanvas-title" id="exampleModalLabel">New Work Shift</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body flex-grow-1">
                <div class="row">
                    <div class="col-sm-12">
                        <x-shift-form />
                    </div>
                </div>
            </div>
          </div>


        </div>

          <!-- / Content -->

          <!-- Footer -->
          <x-footer />
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
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

@push('js')
<script>

  $('#shift_start_time, #shift_end_time, #mini_break_time, #max_break_time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i:S',
            time_24hr: true,
            enableSeconds: true
        });

   /*  flatpickr("#mini_break_time", {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i:S", // 12-hour format
      time_24hr: false // true = 24-hour format; false = 12-hour with AM/PM
    });

    flatpickr("#max_break_time", {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i:S", // 12-hour format
      time_24hr: false // true = 24-hour format; false = 12-hour with AM/PM
    }); */

  </script>
@endpush

