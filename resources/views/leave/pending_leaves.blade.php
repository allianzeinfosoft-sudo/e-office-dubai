@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <x-menu />
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
            <h4 class="fw-semibold mb-4">Pending Requests</h4>
            <!-- Role cards -->
            <div class="row g-4">
                <div class="col-12">
                    <!-- Role Table -->
                    <div class="card">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-leave-pending table border-top">
                          <thead>
                            <tr>
                              <th></th>
                              <th>Leave From</th>
                              <th>Leave To</th>
                              <th>Leave Count</th>
                              <th>Leave Type</th>
                              <th>Leave Reason</th>
                              <th>Apply Date</th>
                              <th>Approved/Cancel</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                    <!--/ Leave Table -->
                  </div>
            </div>
          </div>

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
