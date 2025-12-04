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
            <h4 class="fw-semibold mb-4">Leave Allocates</h4>
            <!-- Leave allocate cards -->
            <div class="row g-4">
                <div class="col-12">
                    <!-- Leave allocate Table -->
                    <div class="card">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-leave-allocate table border-top">
                          <thead>
                            <tr>
                              <th>S.No</th>
                              <th>Name</th>
                              <th>Allocated Leaves</th>
                              <th>Leaves Taken</th>
                              <th>Leave Balance</th>
                              <th>Leave Year</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                    <!--/ Leave allocate Table -->
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

  <!-- Add New Credit Card Modal -->
  <div class="modal fade" id="addLeaves" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Allocate Leave</h3>

          </div>
          <form action="{{route('leaves.leave_allocate')}}" method="post" id="addLeaveAllocateForm" class="row g-3"  >
           <!-- Hidden Fields (If Needed for Form Submission) -->
           @csrf

            <input type="hidden" id="modalUserId" name="modalUserId">

            <!-- Display User Information -->
            <div class="col-md-12">
                <label class="form-label" for="leave_to">Enter allocated leaves</label>
                <div class="input-group input-group-merge">
                  <input type="text" name="leave_count" value="{{ old('leave_count') }}" class="form-control" placeholder="Enter leave count" id="leave_count" />
                </div>
              </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1 submit-button">Submit</button>
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

    var dtLeaveAllocateTable = $('.datatables-leave-allocate');
    // Users List datatable
    if (dtLeaveAllocateTable.length) {
      var dtLeaveAllocate = dtLeaveAllocateTable.DataTable({
          ajax: {
              url: "/allocated-leaves",
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
                var userView = "/user/profile/"+full['user_id'];
                var $name = full['full_name']
                var $row_output = '<div class="d-flex justify-content-start align-items-center user-name">' +
                                    '<div class="d-flex flex-column">' +
                                    '<a href="' +
                                    userView +
                                    '" class="text-body text-truncate"><span class="fw-semibold">' +
                                    $name +
                                    '</span></a>' +

                                    '</div>' +
                                    '</div>';
                return $row_output;
            }
          },
          { data: 'total_leaves', title: 'Allocated Leaves' },
          { data: 'used_leaves', title: 'Leaves Taken' },
          { data: 'remaining_leaves', title: 'Leave Balance' },
          {
            targets: 5, // Column index
            render: function (data, type, full, meta) {
                let selectedYear = full['year']; // Get the selected year from data
                let userId = full['user_id'];
                let leaveId = full['id'];
                let startYear = 2020;
                let endYear = 2029;

                let $selectBox = $("<select  class='year' data-user-id='" + userId + "' data-leave-id='"+ leaveId +"'>").addClass("form-select");

                for (let year = startYear; year <= endYear; year++) {
                    let isSelected = (year == selectedYear) ? "selected" : ""; // Ternary operator to set selected attribute
                    let $option = $(`<option value="${year}" ${isSelected}>`).text(year); // Use template literals correctly

                    $selectBox.append($option);
                }

                return $selectBox.prop("outerHTML");
            }

          },

          {
            targets: 6,
            render: function(data, type, full, meta){
                let user_id = full['user_id'];
                let leave_id = full['id'];
                $buttons = `<button class="btn btn-sm btn-success me-2 open-modal updateLeave" data-user-id="${user_id}"  data-leave-id="${leave_id}" data-bs-toggle="modal" data-bs-target="#addLeaves1">Add Leave &nbsp; <i class="fa fa-check-circle"></i></button>`;
                return $buttons;
            }
          },

        ]

  });
}

 });



//  $(document).on("click", ".open-modal", function() {

//     let userId = $(this).data("id");
//     // Pass values to the modal inputs or elements
//     $("#modalUserId").val(userId);
//     $

// });



$(document).on("change", ".year", function () {


    const year = $(this).val();
    const userId = $(this).data('user-id');
    const leaveId = $(this).data('leave-id');
    const row = $(this).closest('tr'); // Get the current table row

    $.ajax({
        url: '/check-leave',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            user_id: userId,
            year: year
        },
        success: function (response) {
            if (response.leave_exists) {

                // Update leave details dynamically in the row
                $.ajax({
                    url: '/get-leave-details', // A route to fetch leave details
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        user_id: userId,
                        year: year
                    },
                    success: function (leaveDetails) {

                        if (leaveDetails) {
                            alert(leaveDetails.total_leaves);
                            row.find('td:nth-child(3)').html(leaveDetails.total_leaves ?? '0');
                            row.find('td:nth-child(4)').text(leaveDetails.used_leaves ?? '0'); // Leaves Taken
                            row.find('td:nth-child(5)').text(leaveDetails.remaining_leaves ?? '0'); // Leave Balance

                            // Attach an event listener to calculate leave balance dynamically
                            row.find('.allocated-leaves-input').on('input', function () {
                                const allocatedLeaves = parseInt($(this).val()) || 0;
                                const leavesTaken = parseInt(leaveDetails.used_leaves) || 0;

                                // Recalculate leave balance and update the corresponding column
                                const leaveBalance = allocatedLeaves - leavesTaken;
                                row.find('td:nth-child(5)').text(leaveBalance >= 0 ? leaveBalance : 0); // Avoid negative balance
                            });

                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }
                });

            } else {

                // Enable the input dynamically for new leave entry
                row.find('td:nth-child(3)').html('<span> No leave record found for this year, please update! </span>');
                row.find('td:nth-child(4)').text('0');
                row.find('td:nth-child(5)').text('0'); // Leaves Taken
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
});



// update leave allocation
$(document).on("click", ".updateLeave", function () {
        const button = $(this);
        const row = button.closest('tr'); // Get the corresponding row
        const userId = button.data('user-id');
        const leaveId = button.data('leave-id');
        const year = row.find('.year').val(); // Fetch the year from the dropdown
        const leavesTaken = parseInt(row.find('td:nth-child(4)').text()) || 0; // Get Leaves Taken value

        // Prompt user to enter allocated leaves
        const allocatedLeaves = prompt('Enter allocated leaves:');
        if (!allocatedLeaves || isNaN(allocatedLeaves)) {
            alert('Please enter a valid number for allocated leaves.');
            return;
        }

        // Calculate remaining leaves

        const remainingLeaves = allocatedLeaves - leavesTaken;

        $.ajax({
            url: '/update-leave-allocation', // Your backend route
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: userId,
                leave_id: leaveId,
                year: year,
                total_leaves: allocatedLeaves,
                remaining_leaves: remainingLeaves // Avoid negative values
            },
            success: function (response) {
                if (response.success) {
                    // Update Allocated Leaves and Remaining Leaves in the table
                    row.find('td:nth-child(3)').text(allocatedLeaves); // Allocated Leaves
                    row.find('td:nth-child(5)').text(remainingLeaves); // Remaining Leaves

                    alert('Leave allocation updated successfully!');
                    win
                } else {
                    alert('Failed to update leave allocation.');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                alert('An error occurred while updating leave allocation.');
            }
        });
    });




$(document).on('input', '.allocated-leaves-input', function () {
    const row = $(this).closest('tr'); // Get the row containing the input
    const allocatedLeaves = parseInt($(this).val()) || 0; // Get the new allocated leave value
    const leavesTaken = parseInt(row.find('td:nth-child(5)').text()) || 0; // Leaves Taken value from the row

    // Calculate remaining leaves
    const remainingLeaves = allocatedLeaves - leavesTaken;

    // Update the Remaining Leaves column
    row.find('td:nth-child(6)').text(remainingLeaves >= 0 ? remainingLeaves : 0); // Ensure no negative value
});


</script>
@stop
