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
            <h4 class="fw-semibold mb-4">Branch & Department List</h4>
 
            <!-- Permission Table -->
            <div class="card">
              <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table">
                  <thead>
                    <tr>
                      <th></th>
                      <th></th>
                      <th>id</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Date</th>
                      <th>Salary</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <!--/ Branch Table -->

            <!-- Modal -->
            <!-- Modal to add new record -->
            <div class="offcanvas offcanvas-end" id="add-new-branch">
              <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="exampleModalLabel">New Branch</h5>
                <button
                  type="button"
                  class="btn-close text-reset"
                  data-bs-dismiss="offcanvas"
                  aria-label="Close"></button>
              </div>
              <div class="offcanvas-body flex-grow-1">
                <form class="add-new-branch pt-0 row g-2" id="form-add-new-branch" onsubmit="return false">
                  <div class="col-sm-12">
                    <label class="form-label" for="basicBranchname">Branch Name</label>
                    <div class="input-group input-group-merge">
                      <span id="basicBranchname2" class="input-group-text"><i class="ti ti-user"></i></span>
                      <input
                        type="text"
                        id="basicBranchname"
                        class="form-control dt-branch-name"
                        name="branch"
                        placeholder=""
                        aria-label=""
                        aria-describedby="basicBranchname" />
                    </div>
                  </div> 
                  <div class="col-sm-12">
                    <label class="form-label" for="location">Location</label>
                    <div class="input-group input-group-merge">
                      <span class="input-group-text"><i class="ti ti-mail"></i></span>
                      <input
                        type="text"
                        id="location"
                        name="location"
                        class="form-control dt-location"
                        placeholder=""
                        aria-label="" />
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!--/ DataTable with Buttons -->



            <!-- Edit Branch Modal -->
            <div class="modal fade" id="editBranchModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                  <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                  <div class="modal-body">
                    <div class="text-center mb-4">
                      <h3 class="mb-2">Edit Branch</h3>
                      <p class="text-muted">Edit branch as per your requirements.</p>
                    </div>
                    <div class="alert alert-warning" role="alert">
                      <h6 class="alert-heading mb-2">Warning</h6>
                    </div>
                    <form id="editBranchForm" class="row" onsubmit="return false">
                      <div class="col-sm-9">
                        <label class="form-label" for="editBrachName">Branch Name</label>
                        <input type="text" id="editBranchName" name="editBranchName" class="form-control" placeholder="Branch Name" tabindex="-1" />
                      </div>
                      <div class="col-sm-3 mb-3">
                        <label class="form-label invisible d-none d-sm-inline-block">Button</label>
                        <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                      </div> 
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!--/ Edit Permission Modal -->

            <!-- /Modal -->
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



