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
            <h4 class="fw-semibold mb-4">Leave Summary</h4>
            <!-- Role cards -->
            <div class="row g-4">
                @include('components.leave.leave_summary_head');

                    <div class="col-sm-12">
                            <div class="card card-bg mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"> <i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <form id="filter-form"  method="POST" onsubmit="return false;">
                                        @csrf
                                        <div class="row">

                                            <div class="col-sm-3">
                                                <div class="form-group mb-3">
                                                    <label for="year">Year</label>
                                                   <input type="month" name="filter_from_date" id="filter_from_date" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-sm-3 my-auto">
                                                <div class="form-group mb-3 mt-3">
                                                    <button type="submit" class="btn btn-primary">Find</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>




                <div class="col-12">
                    <!-- Role Table -->
                    <div class="card">
                      <div class="card-datatable table-responsive">
                        <table id="summaryTable" class="datatables-leave-summary table border-top">
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
                          <tbody id="reportContainer">
                                <tr>
                                    <td class="text-center" colspan="10">
                                        <div class="alert alert-warning mt-3">
                                                        Loading...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
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
// if (dtLeaveTable.length) {
//   var dtLeave = dtLeaveTable.DataTable({
//       ajax: {
//           url: "/leave-list",  // Fetch from Laravel API
//           type: "GET",
//           dataType: "json",
//           dataSrc: "data"
//       },
//     columns: [
//       // columns according to JSON
//       {
//         data: null, title: 'S.No',
//         render: function (data, type, row, meta){
//             return meta.row+1;
//         }
//       },
//       { data: 'full_name', title: 'Name' },
//       { data: 'leave_from', title: 'Leave From' },
//       { data: 'leave_to', title:  'Leave To'},
//       {
//         targets: 4,
//             render: function(data, type, full, meta){
//                 let leave_count = full['leave_count'];
//                 if(leave_count > 1)
//                 {
//                     $showCount = `<button class="btn btn-sm btn-primary">${leave_count}</button> days`
//                 }
//                 else
//                 {
//                     $showCount = `<button class="btn btn-sm btn-secondary">${leave_count}</button> day`
//                 }

//                 return $showCount;
//             }
//       },
//       {
//         targets: 5,
//             render: function(data, type, full, meta){
//                 let leaveType = full['leave_type'];
//                 let displayType = 'N/A';
//                 if(leaveType === 'half_day')
//                 {
//                     displayType = `<button class="btn btn-sm btn-warning">Half</button>`;
//                 }
//                 else if(leaveType === 'full_day')
//                 {
//                     displayType = `<button class="btn btn-sm btn-info">Full</button>`;
//                 }
//                 else if(leaveType === 'off_day')
//                 {
//                     displayType = `<button class="btn btn-sm btn-primary">Off</button>`
//                 }

//                 return displayType;
//             }
//       },
//       { data: 'leave_reason', title: 'Leave Reason' },
//       { data: 'apply_date', title: 'Apply Date' },
//       { data: 'approved_cancel_date', title: 'Accepeted/Rejected'},
//       {
//         targets:9,
//         render: function(data, type, full, row, meta){
//             let status = full['status'];

//             if(status == 1)
//             {
//                 $status_show = `<button type="button" class="btn btn-sm btn-label-linkedin waves-effect">
//                             Pending
//                           </button>`;
//             }
//             else if(status == 2)
//             {
//                 $status_show = `<button type="button" class="btn btn-sm btn-success waves-effect">
//                               Approved
//                           </button>`;
//             }
//             else if(status == 3)
//             {
//                 $status_show = `<button type="button" class="btn btn-sm btn-danger waves-effect">
//                               Rejected
//                           </button>`;
//             }
//             else if(status == 4)
//             {
//                 $status_show = `<button type="button" class="btn btn-sm btn-label-linkedin waves-effect">
//                              Cancelled by user
//                           </button>`;
//             }

//             return $status_show;
//         }
//       }
//     ],

//   });
// }



let table;
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#summaryTable')) {

        table = $('#summaryTable').DataTable({
            processing: true,
            serverSide: false,
            searching: false,
            paging: true,
            ordering: false,
            ajax: {
                url: '/leave_summary_filter',
                type: 'POST',
                data: function () {
                    return $('#filter-form').serialize(); // send form data
                },
                dataSrc: 'data', // Laravel should return { data: [...] }
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF for Laravel
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            },
            columns: [
                {
                    data: null,
                    title: 'S.No',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'full_name', title: 'Name' },
                { data: 'leave_from', title: 'Leave From' },
                { data: 'leave_to', title: 'Leave To' },
                {
                    data: 'leave_count',
                    title: 'Leave Count',
                    render: function (data) {
                        const label = data > 1 ? 'days' : 'day';
                        const btnClass = data > 1 ? 'btn-primary' : 'btn-secondary';
                        return `<button class="btn btn-sm ${btnClass}">${data}</button> ${label}`;
                    }
                },
                {
                    data: 'leave_type',
                    title: 'Leave Type',
                    render: function (data) {
                        switch (data) {
                            case 'half_day': return `<button class="btn btn-sm btn-warning">Half</button>`;
                            case 'full_day': return `<button class="btn btn-sm btn-info">Full</button>`;
                            case 'off_day': return `<button class="btn btn-sm btn-primary">Off</button>`;
                            default: return 'N/A';
                        }
                    }
                },
                { data: 'leave_reason', title: 'Leave Reason' },
                { data: 'apply_date', title: 'Apply Date' },
                { data: 'approved_cancel_date', title: 'Accepted/Rejected' },
                {
                    data: 'status',
                    title: 'Status',
                    render: function (data) {
                        if (data == 1) return `<button class="btn btn-sm btn-label-linkedin">Pending</button>`;
                        if (data == 2) return `<button class="btn btn-sm btn-success">Approved</button>`;
                        if (data == 3) return `<button class="btn btn-sm btn-danger">Rejected</button>`;
                        if (data == 4) return `<button class="btn btn-sm btn-label-linkedin">Cancelled</button>`;
                        return 'N/A';
                    }
                }
            ]
        });
    } else {
        table = $('#summaryTable').DataTable(); // get existing instance
    }

    $('#filter-form').on('submit', function (e) {
        e.preventDefault();

        const fromDate = $('#filter_from_date').val();
        if (!fromDate) {
            alert('Please select a date.');
            return;
        }

        table.ajax.reload();
    });

    $('#filter_from_date').val(new Date().toISOString().slice(0, 7));
});





});
</script>
@stop

