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
            <h4 class="fw-semibold mb-4">Leave Summary</h4>
            <!-- Role cards -->
            <div class="row g-4">
                @include('components.leave.leave_summary_head');
                <div class="col-12">
                    <!-- Role Table -->
                    <div class="card">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-users table border-top">
                          <thead>
                            <tr>
                              <th></th>
                              <th>User</th>
                              <th>Role</th>
                              <th>Plan</th>
                              <th>Billing</th>
                              <th>Status</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                    <!--/ Role Table -->
                  </div>




            </div>
            <!--/ Role cards -->

            <!-- Add Role Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                <div class="modal-content p-3 p-md-5">
                  <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                  <div class="modal-body">
                    <div class="text-center mb-4">
                      <h3 class="role-title mb-2">Add New Role</h3>
                      <p class="text-muted">Set role permissions</p>
                    </div>
                    <!-- Add role form -->
                    <form id="addRoleForm" method="post" action="{{ route('roles.store') }}" class="row g-3" onsubmit="return false">
                      @csrf
                      <div class="col-12 mb-4">
                        <label class="form-label" for="name">Role Name</label>
                        <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="Enter a role name"
                          tabindex="-1" />
                        <input type="hidden" name="guard_name" value="web"/>
                      </div>
                      <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive" id="permissionsTable">
                        </div>
                        <!-- Permission table -->
                      </div>
                      <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                          Cancel
                        </button>
                      </div>
                    </form>
                    <!--/ Add role form -->
                  </div>
                </div>
              </div>
            </div>
            <!--/ Add Role Modal -->

            <!-- / Add Role Modal -->
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
