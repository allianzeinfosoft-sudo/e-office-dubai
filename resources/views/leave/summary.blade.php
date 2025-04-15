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
                        <table class="datatables-leave-summary table border-top">
                          <thead>
                            <tr>
                              <th>S.No</th>
                              <th>Name</th>
                              <th>Leave From</th>
                              <th>Leave To</th>
                              <th>Leave Count</th>
                              <th>Leave Type</th>
                              <th>Leave Reason</th>
                              <th>Apply Date</th>
                              <th>Accepeted/Rejected</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                    <!--/ Role Table -->
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


@section('js')
<script>
$(function () {

  var dtLeaveTable = $('.datatables-leave-summary')

// Leave List datatable
if (dtLeaveTable.length) {
  var dtLeave = dtLeaveTable.DataTable({
      ajax: {
          url: "/leave-list",  // Fetch from Laravel API
          type: "GET",
          dataType: "json",
          dataSrc: "data"
      },
    columns: [
      // columns according to JSON
      {
        data: null, title: 'S.No',
        render: function (data, type, row, meta){
            return meta.row+1;
        }
      },
      { data: 'full_name', title: 'Name' },
      { data: 'leave_from', title: 'Leave From' },
      { data: 'leave_to', title:  'Leave To'},
      {
        targets: 4,
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
        targets: 5,
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
      { data: 'approved_cancel_date', title: 'Accepeted/Rejected'},
      {
        targets:9,
        render: function(data, type, full, row, meta){
            let status = full['status'];

            if(status == 1)
            {
                $status_show = `<button type="button" class="btn btn-sm btn-label-linkedin waves-effect">
                            Pending
                          </button>`;
            }
            else if(status == 2)
            {
                $status_show = `<button type="button" class="btn btn-sm btn-success waves-effect">
                              Approved
                          </button>`;
            }
            else if(status == 3)
            {
                $status_show = `<button type="button" class="btn btn-sm btn-danger waves-effect">
                              Rejected
                          </button>`;
            }
            else if(status == 4)
            {
                $status_show = `<button type="button" class="btn btn-sm btn-label-linkedin waves-effect">
                             Cancelled by user
                          </button>`;
            }

            return $status_show;
        }
      }
    ]
  });
}

});
</script>
@stop

