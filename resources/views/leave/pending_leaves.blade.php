@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
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
                        <table class="datatables-pending-request table border-top">
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
                              <th>Accept/Reject</th>

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
  <!--/  Add New Credit Card -->

  <!-- Add New Credit Card Modal -->
  <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2"></h3>
            <p class="text-muted">Respond to the leave request to complete the leave process.</p>
          </div>
          <form action="{{route('leaves.leave_action')}}" method="post" id="addNewCCForm" class="row g-3"  >
           <!-- Hidden Fields (If Needed for Form Submission) -->
           @csrf

            <input type="hidden" id="modalLeaveId" name="modalLeaveId">
            <input type="hidden" id="modalFunctionType" name="modalFunctionType">

            <!-- Display User Information -->
            <div class="mb-3">
                <strong>Employee Name:</strong> <span id="modalUserName"></span>
            </div>
            <div class="mb-3">
                <strong>Leave From:</strong> <span id="modalLeaveFrom"></span>
            </div>
            <div class="mb-3">
                <strong>Leave To:</strong> <span id="modalLeaveTo"></span>
            </div>
            <div class="mb-3">
                <strong>Reason:</strong> <span id="modalLeaveReason"></span>
            </div>

            <div class="mb-3" id="commentBox">
                <strong>Comment:</strong>
                <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <span>Are you sure you want to <span id="function_name"></span> this leave request? </span>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1 submit-button"></button>
              <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Add New Credit Card Modal -->


@endsection

@section('js')
<script>


 $(function () {

    var dtLeavePendingTable = $('.datatables-pending-request');
    // Users List datatable
    if (dtLeavePendingTable.length) {
      var dtLeavePending = dtLeavePendingTable.DataTable({
          ajax: {
              url: "/leave-pending",
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
          {
             targets:1,
            render: function(data, type, full){
               let $image = full['avatar'],
                userView = "/user/profile/"+full['user_id'];
                var $name = full['full_name']

               if ($image) {
                // For Avatar image
                var $output = '<img src="/storage/' + $image + '" alt="Avatar" class="rounded-circle">';
                } else {
                // For Avatar badge
                var stateNum = Math.floor(Math.random() * 6);
                var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                var $state = states[stateNum],

                $initials = $name.match(/\b\w/g) || [];
                $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
                }
                var $row_output = '<div class="d-flex justify-content-start align-items-center user-name">' +
                                    '<div class="avatar-wrapper">' +
                                    '<div class="avatar avatar-sm me-3">' +
                                    $output +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="d-flex flex-column">' +
                                    '<a href="' +
                                    userView +
                                    '" class="text-body text-truncate"><span class="fw-semibold">' +
                                    $name +
                                    '</span></a>' +

                                    '</div>' +
                                    '</div>';
                                    return $row_output;
                return $row_output;
            }
          },
          { data: 'leave_from', title: 'Leave From' },
          { data: 'leave_to', title: 'Leave To' },
          {
            targets: 5,
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
            targets: 6,
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
            targets: 10,
            render: function(data, type, full, meta){
                let leave_id = full['id'];
                let userName = full['full_name'];
                let leaveFrom = full['leave_from'];
                let leaveTo = full['leave_to'];
                let leaveReason = full['leave_reason'];
                let login_user = full['login_user'];
                let approver = full['leave_approver'];
                let login_user_group = full['login_user_group'];
                if(login_user == approver || login_user_group == 'HR')
                {
                    $buttons = `<button class="btn btn-sm btn-success me-2 open-modal" data-function="1" data-usergroup="${login_user_group}"  data-id="${leave_id}" data-name="${userName}" data-leave-from="${leaveFrom}" data-leave-to="${leaveTo}" data-reason="${leaveReason}" data-bs-toggle="modal" data-bs-target="#addNewCCModal"><i class="fa fa-check-circle"></i></button>`+
                           `<button class="btn btn-sm btn-danger open-modal" data-function="2" data-usergroup="${login_user_group}" data-id="${leave_id}" data-name="${userName}" data-leave-from="${leaveFrom}" data-leave-to="${leaveTo}" data-reason="${leaveReason}" data-bs-toggle="modal" data-bs-target="#addNewCCModal"><i class="fa fa-times-circle"></i></button>`;


                }
                else
                {
                    $buttons = `<button class="btn btn-sm btn-primary me-2><i class="fa fa-clock-o"></i>Pending</button>`
                }

                 return $buttons;

            }
          },

        ]

  });
}

 });



 $(document).on("click", ".open-modal", function() {

    let leaveId = $(this).data("id");
    let functionType = $(this).data("function");
    let userName = $(this).data("name");
    let leaveFrom = $(this).data("leave-from");
    let leaveTo = $(this).data("leave-to");
    let leaveReason = $(this).data("reason");
    let userGroup = $(this).data("usergroup");

    // Pass values to the modal inputs or elements
    $("#modalLeaveId").val(leaveId);
    $("#modalFunctionType").val(functionType);
    $("#modalUserName").text(userName);
    $("#modalLeaveFrom").text(leaveFrom);
    $("#modalLeaveTo").text(leaveTo);
    $("#modalLeaveReason").text(leaveReason);

    if (userGroup === "HR") {
        $("#commentBox").show();
    } else {
        $("#commentBox").hide();
    }


    // Change modal title based on function type
    let modalTitle = functionType == 1 ? "Accept Leave" : "Reject Leave";
    $("#addNewCCModal .text-center h3").text(modalTitle);
    $("#function_name").text(modalTitle);
    $(".submit-button").text(modalTitle);
});



</script>
@stop
