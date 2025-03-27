@extends('layouts.app')
@section('content')
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
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
              <table class="datatables-basic table">
                <thead>
                  <tr>

                    <th></th>
                    <th></th>
                    <th>Shift ID</th>
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
              <form class="add-new-record pt-0 row g-2" method="post" action="{{ route('store.workshift') }}" id="form-add-new-shift" onsubmit="return false">
                @csrf

                <div class="col-sm-12">
                    <label class="form-label" for="shift_id">Shitf ID</label>
                    <div class="input-group input-group-merge">
                      <span id="basicFullname2" class="input-group-text"><i class="ti ti-id"></i></span>
                      <input type="text" id="shift_id" class="form-control dt-shift_id" name="shift_id"/>
                    </div>
                  </div>
                <div class="col-sm-12">
                  <label class="form-label" for="shift_start_time">Shitf Start Time</label>
                  <div class="input-group input-group-merge">
                    <span id="basicFullname2" class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="time" id="shift_start_time" class="form-control dt-shift-start" name="shift_start_time"/>
                  </div>
                </div>
                <div class="col-sm-12">
                  <label class="form-label" for="shift_end_time">Shift End Time</label>
                  <div class="input-group input-group-merge">
                    <span id="basicPost2" class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="time" id="shift_end_time" name="shift_end_time" class="form-control dt-shift-end" />
                  </div>
                </div>
                <div class="col-sm-12">
                  <label class="form-label" for="mini_break_time">Mini Break Time</label>
                  <div class="input-group input-group-merge">
                    <span  class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="time" id="mini_break_time" name="mini_break_time" class="form-control dt-min-break" />
                  </div>
                </div>
                <div class="col-sm-12">
                  <label class="form-label" for="max_break_time">Max Break Time</label>
                  <div class="input-group input-group-merge">
                    <span id="basicDate2" class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="time" class="form-control dt-max-break" id="max_break_time" name="max_break_time" />
                  </div>
                </div>

                <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                  <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
              </form>
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



