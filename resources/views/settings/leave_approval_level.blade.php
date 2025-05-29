@extends('layouts.app')
@section('css')
<style>
.w-35 {
    width: 35% !important;
}
.w-45 {
    width: 45% !important;
}
.offcanvas-close{
    position: absolute;
    top: 0px;
    left: -32px;  /* Moves the button outside the offcanvas */
    z-index: 1055; /* Ensures it stays on top */
    padding: 28px 10px;
    border-radius: 0px;
}
</style>
@stop
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
          {{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">DataTables /</span> Basic</h4> --}}

          <!-- DataTable with Buttons -->
          <div class="card">
            <div class="card-datatable table-responsive">
              <table class="datatables-leave-approver table border-top" id="datatables-leave-approver">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Department</th>
                    <th>Approval Level</th>
                    <th>Approver</th>
                    {{-- <th>Approval Count</th> --}}
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <!-- Modal to add new record -->

          <div class="offcanvas offcanvas-end" id="add-leave-approvals">
            <div class="offcanvas-header border-bottom bg-primary p-3">
                <span class="d-flex justify-content-between align-items-center gap-2">
                    <i class="ti ti-file-plus fs-2 text-white"></i>
                    <span id="offcanvas-title-container">
                        <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Leave Approvals</h5>
                        <span class="text-white slogan">Create New Leave Approvals</span>
                    </span>
                </span>
                <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
            </div>
            <div class="offcanvas-body flex-grow-1" style="overflow: visible!important;">
              <div class="row">
                <div class="col-sm-12">
                    <x-leave-approval-from />
                </div>
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


@section('js')
<script>


$(function () {

     setTimeout(() => {
      const newRecord = document.querySelector('.create-new'),
        offCanvasElement = document.querySelector('#add-leave-approvals');

      // To open offCanvas, to add new record
      if (newRecord) {
        newRecord.addEventListener('click', function () {
          offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
          // Empty fields on offCanvas open

        //   (offCanvasElement.querySelector('.dt-holiday-name').value = ''),
        //   (offCanvasElement.querySelector('.dt-holiday-date').value = ''),

          // Open offCanvas with form
          offCanvasEl.show();
        });
      }
    }, 200);




var dtLeaveApproverTable = $('.datatables-leave-approver');
// Users List datatable
if (dtLeaveApproverTable.length) {
        dtLeaveApproverTable.DataTable({
            ajax: {
                url: "/leave_approver/list",
                type: "GET",
                dataType: "json",
                dataSrc: "data"
            },
            columns: [
            // columns according to JSON
            {   data: null,
                title: 'S.No',
                render: function (data, type, row, meta){
                    return meta.row+1;
                }
            },
            { data: 'department', title: 'Department' },
            // { data: 'level', title: 'Approval Level' },
            {
                data: 'level',
                title: 'Approval Level',
                render: function (data, type, row) {
                    if (row['level'] == 2) return '2nd Level';
                    if (row['level'] == 3) return 'Final Approver';
                    return data; // fallback for other values
                }
            },
            { data: 'approver', title: 'Approver'},

            {
                data: null,
                title: 'Action',
                render: function(data, type, full, meta){
                    let approver_id = full['id'];
                    $buttons = `<a href="javascript:;" class="text-body delete-approver" data-id="${approver_id}"><i class="ti ti-trash ti-sm mx-2"></i></a>`;
                    return $buttons;
                }
            },

            ],

              dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
              displayLength: 7,
              lengthMenu: [7, 10, 25, 50, 75, 100],
              buttons: [

                {
                  text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Leave Approver</span>',
                  className: 'create-new btn btn-primary'
                }
              ]


        });
    $('div.head-label').html('<h5 class="card-title mb-0">Leave Approvals</h5>');
    }


// submit form

        const form = document.getElementById('form-add-leave-approval');
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            const department = document.getElementById('department').value.trim();
            const approval_level = document.getElementById('approval-level').value.trim();
            const approver = document.getElementById('approver').value.trim();
            // const approve_count = document.getElementById('approve-count').value.trim();
            let errors = [];

            // === Validation ===
            if (!department) {
                errors.push("Department is required");
            }

            if (!approval_level) {
                errors.push("Approvel level is required.");
            }

            if (!approver) {
                errors.push("Approver is required");
            }

            // if (!approve_count) {
            //     errors.push("Approver count is required");
            // }

            // === Show errors or submit ===
            let errorBox = document.getElementById('formErrors');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'formErrors';
                errorBox.className = 'alert alert-danger mt-3';
                form.prepend(errorBox);
            }

            if (errors.length > 0) {
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else {
                errorBox.innerHTML = ''; // Clear old errors
                form.submit(); // Submit manually only if no errors
            }
        });



/*delete leave function*/

    $(document).on('click', '.delete-approver', function(e) {
        e.preventDefault();
        const approverId = $(this).data('id');

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
                            url: `/approver-delete/${approverId}`,
                            type: 'DELETE',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                            },
                            success: function(response) {

                                Swal.fire("Deleted!", "Leave approver has been deleted.", "success").then(() => {
                                    $('#datatables-leave-approver').DataTable().ajax.reload(); // Reload table
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



