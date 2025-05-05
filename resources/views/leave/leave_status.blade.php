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
            <h4 class="fw-semibold mb-4">Leave Status</h4>
            <!-- Role cards -->
            <div class="row g-4">
                <div class="col-12">
                    <!-- Role Table -->
                    <div class="card">
                       <meta name="auth-user-id" content="{{ Auth::user()->id }}">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-leave-status table border-top" id="leave-status-table">
                          <thead>
                            <tr>
                              <th>S.No</th>
                              <th>Leave From</th>
                              <th>Leave To</th>
                              <th>Leave Count</th>
                              <th>Leave Type</th>
                              <th>Leave Reason</th>
                              <th>Apply Date</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                    <!--/ Role Table -->
                  </div>
            </div>
            <!--/ Role cards -->
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

@section('js')
<script>


$(function () {
    var dtLeaveStatusTable = $('.datatables-leave-status');
    var userId = document.querySelector('meta[name="auth-user-id"]').getAttribute('content');
    // Users List datatable
    if (dtLeaveStatusTable.length) {
      var dtLeaveStatus = dtLeaveStatusTable.DataTable({
          ajax: {
              url: "/leave-status/" + userId,
              type: "GET",
              dataType: "json",
              dataSrc: "data"
          },
        columns: [
          // columns according to JSON
          { data: null, title: 'S.No',
            render: function (data, type, row, meta){
                return meta.row+1;
            }
          },
          { data: 'leave_from', title: 'Leave From'},
          { data: 'leave_to', title: 'Leave To' },
          {

            targets: 3,
            render: function(data, type, full, meta){
                let leave_count = full['leave_count'];
                if(leave_count > 1)
                {
                    $showCount = `<button class="btn btn-sm btn-primary">${leave_count}</button> days`
                }
                else
                {
                    $showCount = `<button class="btn btn-sm btn-secondary">${leave_count}</button> day`
                }

                return $showCount;
            }

           },
          {
            targets: 4,
            render: function(data, type, full, meta){
                let leaveType = full['leave_type'];
                let displayType = 'N/A';
                if(leaveType === 'half_day')
                {
                    displayType = `<button class="btn btn-sm btn-warning">Half</button>`;
                }
                else if(leaveType === 'full_day')
                {
                    displayType = `<button class="btn btn-sm btn-info">Full</button>`;
                }

                return displayType;
            }
          },
          { data: 'leave_reason', title: 'Leave Reason' },
          { data: 'apply_date', title: 'Apply Date' },
          {
            targets: 8,
            render: function(data, type, full, meta){
                return `<button type="button" class="btn btn-sm btn-label-pinterest waves-effect">
                            Pending
                          </button>`;
            }

           },
           {
            targets: 9,
            render:function(data, type, full, mete){
                return `<a href="javascript:void(0)" class="delete-leave" data-id="${full.id}"><i class="ti ti-trash"></i></a>`;
            }
           }
        ],

      });
    }


    /*delete leave function*/

    $(document).on('click', '.delete-leave', function(e) {
        e.preventDefault();
        const leaveId = $(this).data('id');

        Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                        $.ajax({
                        url: `/leaves/${leaveId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Leave has been deleted.", "success").then(() => {
                                $('#leave-status-table').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });
});

</script>
@stop
