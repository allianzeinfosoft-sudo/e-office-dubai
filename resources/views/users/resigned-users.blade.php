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
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <!-- Users List Table -->
            <div class="card">
              <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Resigned Users</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                  <div class="col-md-4 user_role"></div>
                  <div class="col-md-4 user_plan"></div>
                  <div class="col-md-4 user_status"></div>
                </div>
              </div>

              <div class="card-datatable table-responsive">
                <table id="lockUserTable" class="datatables-lock-users table border-top">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Name</th>
                      <th>Employee ID</th>
                      <th>Username</th>
                      <th>Contact Number</th>
                      <th>Role</th>
                      <th>Group</th>
                      <th>Status</th>
                      <th>Resigned Date</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <!-- Offcanvas to add new user -->
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
const currentUserRoles = @json(Auth::user()->getRoleNames());
$(function () {

    statusObj = {
      1: { title: 'New User', class: 'bg-label-warning' },
      2: { title: 'Active', class: 'bg-label-success' },
      3: { title: 'Inactive', class: 'bg-label-secondary' },
      4: { title: 'Resigned', class: 'bg-label-danger'},
      5: { title: 'Admin', class: 'bg-label-primary'}
    };


    var lockUsersTable = $('.datatables-lock-users');

    if (lockUsersTable.length) {
        lockUsersTable.DataTable({
            ajax: {
                type: "GET",
                url: "{{ route('locked.users.view') }}", // Route should return JSON
                dataType: "json",
                dataSrc: "data"
            },
            columns: [
                { data: '' },
                { data: 'full_name' },
                { data: 'employeeID' },
                { data: 'email' },
                { data: 'phonenumber' },
                { data: 'role' },
                { data: 'group', title: 'Group' },
                { data: 'status' },
                { data: 'id' } // for actions
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: 1,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {
                        let userView = "/user/profile/" + full['id'];
                        let name = full['full_name'] || '';
                        let email = full['email'] || '';
                        let image = full['profile_image'];
                        let output = '';

                        if (image) {
                            output = '<img src="/storage/' + image + '" alt="Avatar" class="rounded-circle">';
                        } else {
                            let stateNum = Math.floor(Math.random() * 6);
                            let states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                            let state = states[stateNum];
                            let initials = (name.match(/\b\w/g) || []).join('').substring(0, 2).toUpperCase();
                            output = '<span class="avatar-initial rounded-circle bg-label-' + state + '">' + initials + '</span>';
                        }

                        return (
                            '<div class="d-flex justify-content-start align-items-center user-name">' +
                                '<div class="avatar-wrapper">' +
                                    '<div class="avatar avatar-sm me-3">' +
                                        output +
                                    '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                    '<a href="' + userView + '" class="text-body text-truncate"><span class="fw-semibold">' + name + '</span></a>' +
                                    '<small class="text-muted">' + email + '</small>' +
                                '</div>' +
                            '</div>'
                        );
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, full) {
                        return "<span class='text-truncate d-flex align-items-center'>" + (full['role'] || '') + "</span>";
                    }
                },
                {
                    // User Status
                    targets: 7,
                    render: function (data, type, full, meta) {
                            var $status = full['status'];
                            var status = statusObj[$status];

                            if (!status) {
                                return '<span class="badge bg-secondary text-capitalized">Unknown</span>';
                            }

                            return (
                                '<span class="badge ' + status.class + ' text-capitalized">' + status.title + '</span>'
                            );
                        }
                },
                {
                    targets: 8,
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full) {
                        const user_id = full['id'];
                        let action = '';
                       if (typeof currentUserRoles !== 'undefined') {
                        if (currentUserRoles.includes("HR") || currentUserRoles.includes("Developer")) {
                            action = `
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="text-body" title="Unlock User" onclick="restore(${user_id})"><i class="ti-xs ti ti-lock me-1"></i></a>
                                </div>
                            `;
                        }

                        return action;
                    }
                }
                }
            ],
            order: [[0, 'asc']]
        });
    }
});


 function restore(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, restore it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/user-restore/${userId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Restored!", "User has been restored.", "success").then(() => {
                            $('#lockUserTable').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire("Error!", "Could not delete user.", "error");
                });
            }
        });
    }
</script>
@endpush

