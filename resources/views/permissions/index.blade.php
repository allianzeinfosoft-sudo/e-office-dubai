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
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">Permissions List</h4>

            <!-- Permission Table -->
            <div class="card">
              <div class="card-datatable table-responsive">
                <table class="datatables-permissions table border-top">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Sl No</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Assigned To</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <!--/ Permission Table -->

            <!-- Modal -->
            <!-- Add Permission Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                  <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                  <div class="modal-body">
                    <div class="text-center mb-4">
                      <h3 class="mb-2">Add New Permission</h3>
                      <p class="text-muted">Permissions you may use and assign to your users.</p>
                    </div>
                    <form id="addPermissionForm" method="post" action="{{ route('permissions.store') }}" class="row" onsubmit="return false">
                      @csrf
                      <div class="col-12 mb-3">
                        <label class="form-label" for="name">Permission Category</label>
                        <select name="permission_category_id" class="form-select" id="permission_category_id">
                            <option>select-category</option>
                            @foreach ($permission_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                      <div class="col-12 mb-3">
                        <label class="form-label" for="name">Permission Name</label>
                          <input type="text" id="modalPermissionName" name="name" class="form-control" placeholder="Permission Name" autofocus />
                        <input type="hidden" value="web" name="guard_name">
                      </div>
                      <div class="col-12 mb-2">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="corePermission" />
                          <label class="form-check-label" for="corePermission"> Set as core permission </label>
                        </div>
                      </div>
                      <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Create Permission</button>
                        <button
                          type="reset"
                          class="btn btn-label-secondary"
                          data-bs-dismiss="modal"
                          aria-label="Close">
                          Discard
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!--/ Add Permission Modal -->


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



