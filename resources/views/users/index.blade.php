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
            <div class="row g-4 mb-4">
              <div class="col-sm-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                      <div class="content-left">
                        <span>Session</span>
                        <div class="d-flex align-items-center my-1">
                          <h4 class="mb-0 me-2">21,459</h4>
                          <span class="text-success">(+29%)</span>
                        </div>
                        <span>Total Users</span>
                      </div>
                      <span class="badge bg-label-primary rounded p-2">
                        <i class="ti ti-user ti-sm"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                      <div class="content-left">
                        <span>Paid Users</span>
                        <div class="d-flex align-items-center my-1">
                          <h4 class="mb-0 me-2">4,567</h4>
                          <span class="text-success">(+18%)</span>
                        </div>
                        <span>Last week analytics </span>
                      </div>
                      <span class="badge bg-label-danger rounded p-2">
                        <i class="ti ti-user-plus ti-sm"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                      <div class="content-left">
                        <span>Active Users</span>
                        <div class="d-flex align-items-center my-1">
                          <h4 class="mb-0 me-2">19,860</h4>
                          <span class="text-danger">(-14%)</span>
                        </div>
                        <span>Last week analytics</span>
                      </div>
                      <span class="badge bg-label-success rounded p-2">
                        <i class="ti ti-user-check ti-sm"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-xl-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                      <div class="content-left">
                        <span>Pending Users</span>
                        <div class="d-flex align-items-center my-1">
                          <h4 class="mb-0 me-2">237</h4>
                          <span class="text-success">(+42%)</span>
                        </div>
                        <span>Last week analytics</span>
                      </div>
                      <span class="badge bg-label-warning rounded p-2">
                        <i class="ti ti-user-exclamation ti-sm"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
              <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                  <div class="col-md-4 user_role"></div>
                  <div class="col-md-4 user_plan"></div>
                  <div class="col-md-4 user_status"></div>
                </div>
              </div>

             

              <div class="card-datatable table-responsive">
                <div class=" float-end mt-15 mr-20">
                  <a href="users/create">  
                    <button class="btn btn-secondary add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button">
                      <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New User</span>
                      </span>
                    </button>
                  </a>
                </div>

                <table class="datatables-users table border-top">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Name</th>
                      <th>Role</th>
                      <th>Username</th>
                      <th>Contact Number</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <!-- Offcanvas to add new user -->
              <div
                class="offcanvas offcanvas-end"
                tabindex="-1"
                id="offcanvasAddUser"
                aria-labelledby="offcanvasAddUserLabel">
                <div class="offcanvas-header">
                  <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
                  <button
                    type="button"
                    class="btn-close text-reset"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                  <form class="add-new-user pt-0" id="addNewUserForm" method="post" action="{{ route('users.store') }}" onsubmit="return false">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label" for="add-user-fullname">Full Name</label>
                      <input
                        type="text"
                        class="form-control"
                        id="add-user-name"
                        placeholder="Enter User Name"
                        name="userFullname"
                        aria-label="enter user name" />
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="add-user-email">Email</label>
                      <input
                        type="text"
                        id="add-user-email"
                        class="form-control"
                        placeholder="Enter User Email"
                        aria-label="enter user email"
                        name="userEmail" />
                    </div> 
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                  </form>
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
