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
                    <th>Shift Start Time</th>
                    <th>Shift End Time</th>
                    <th>Login Limited</th>
                    <th>Min Break Time</th>
                    <th>Max Break Time </th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>

          <!-- Modal to add new record -->
          <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1"  id="add-new-shift" aria-labelledby="staticBackdropLabel">
            <div class="offcanvas-header border-bottom bg-primary p-3">
              <span class="d-flex justify-content-between align-items-center gap-2">
                 <i class="ti ti-file-plus fs-2 text-white"></i>
                  <span id="offcanvas-title-container">
                      <h5 class="offcanvas-title" id="exampleModalLabel">New Work Shift</h5>
                      <span class="text-white slogan">Add New Work Shift</span>
                  </span>
              </span>
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

          <!-- Working Days Offcanvas -->
          <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvas-working-days" aria-labelledby="workingDaysLabel" style="width: 600px;">
            <div class="offcanvas-header border-bottom bg-primary p-3">
              <span class="d-flex justify-content-between align-items-center gap-2">
                 <i class="ti ti-calendar fs-2 text-white"></i>
                  <span>
                      <h5 class="offcanvas-title text-white" id="working-days-offcanvas-title">Configure Working Days</h5>
                      <span class="text-white slogan">Set working days and times for this shift</span>
                  </span>
              </span>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body flex-grow-1">
                <form id="form-working-days" action="{{ route('store.workshift.details') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="workshift_id" id="workshift_id_detail">
                    <input type="hidden" name="target_detail_id" id="target_detail_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="working_day">Select Day</label>
                            <select id="working_day" name="day" class="form-control">
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="detail_start_time">Start Time</label>
                            <input type="text" id="detail_start_time" name="shift_start_time" class="form-control flatpickr-time" placeholder="HH:MM:SS" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="detail_end_time">End Time</label>
                            <input type="text" id="detail_end_time" name="shift_end_time" class="form-control flatpickr-time" placeholder="HH:MM:SS" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="detail_min_break">Min Break Time</label>
                            <input type="text" id="detail_min_break" name="mini_break_time" class="form-control flatpickr-time" placeholder="HH:MM:SS" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="detail_max_break">Max Break Time</label>
                            <input type="text" id="detail_max_break" name="max_break_time" class="form-control flatpickr-time" placeholder="HH:MM:SS" />
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Save Day Configuration</button>
                            <button type="button" class="btn btn-label-secondary" onclick="resetDetailForm()">Reset</button>
                        </div>
                    </div>
                </form>

                <hr>

                <h6 class="mt-4">Configured Days</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Times</th>
                                <th>Breaks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="working-days-list">
                            <!-- Items will be loaded here -->
                        </tbody>
                    </table>
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

  $('#shift_start_time, #shift_end_time, #login_limited_time, #mini_break_time, #max_break_time, .flatpickr-time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i:S',
            time_24hr: true,
            enableSeconds: true,
            allowInput: true
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

