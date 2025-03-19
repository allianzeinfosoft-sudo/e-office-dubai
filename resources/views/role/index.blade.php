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
            <h4 class="fw-semibold mb-4">Roles List</h4>

            <p class="mb-4">
              A role provided access to predefined menus and features so that depending on <br />
              assigned role an administrator can have access to what user needs.
            </p>
            <!-- Role cards -->
            <div class="row g-4">


                @foreach ($roles as $role )
                  <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex justify-content-between">
                          <h6 class="fw-normal mb-2">Total 2 users</h6>
                          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">

                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              title="Kim Merchent"
                              class="avatar avatar-sm pull-up">
                              <img class="rounded-circle" src="../../assets/img/avatars/10.png" alt="Avatar" />
                            </li>

                          </ul>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-1">
                          <div class="role-heading">
                            <h4 class="mb-1">{{ $role->name ?? ''}}</h4>
                            <a
                              href="javascript:;"
                              data-bs-toggle="modal"
                              data-bs-target="#addRoleModal"
                              class="role-edit-modal"
                              data-role-id={{ $role->id }}
                              ><span>Edit/View Role</span></a>
                          </div>
                          <a href="javascript:void(0);" class="text-muted"><i class="ti ti-copy ti-md"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
              @endforeach





              <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                  <div class="row h-100">
                    <div class="col-sm-5">
                      <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                        <img
                          src="../../assets/img/illustrations/add-new-roles.png"
                          class="img-fluid mt-sm-4 mt-md-0"
                          alt="add-new-roles"
                          width="83" />
                      </div>
                    </div>
                    <div class="col-sm-7">
                      <div class="card-body text-sm-end text-center ps-sm-0">
                        <button
                          data-bs-target="#addRoleModal"
                          data-bs-toggle="modal"
                          data-role-id=""
                          class="btn btn-primary mb-2 text-nowrap add-new-role add-role-model">
                          Add New Role
                        </button>
                        <p class="mb-0 mt-1">Add role, if it does not exist</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <!-- Role Table -->
                      {{-- <div class="card">
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
                      </div> --}}
                <!--/ Role Table -->
              </div>
            </div>
            <!--/ Role cards -->

            <!-- Add Role Modal -->
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
