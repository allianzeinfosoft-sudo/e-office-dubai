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
              <table class="datatables-holidays table border-top">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Name of Holiday</th>
                    <th>Date of Holiday</th>
                    <th>Holiday Group</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <!-- Modal to add new record -->
          <div class="offcanvas offcanvas-end" id="add-new-holiday">
            <div class="offcanvas-header border-bottom">
                <span class="d-flex justify-content-between align-items-center gap-2">
                    <i class="ti ti-file-plus fs-2 text-white"></i>
                    <span id="offcanvas-title-container">
                        <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Holidayt</h5>
                        <span class="text-white slogan">Create New Holiday</span>
                    </span>
                </span>
                <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
            </div>
            <div class="offcanvas-body flex-grow-1">
              <form class="add-new-record pt-0 row g-2" method="post" action="{{ route('holidays.store') }}" id="form-add-new-shift">
                @csrf

                <div class="col-sm-12">
                    <label class="form-label" for="shift_id">Holiday Group</label>
                    <select name="holiday_group" id="holiday_group" class="form-control">
                        @foreach (config('optionsData.holiday_group') as $key => $value)
                            <option value="{{ $key }}">{{ $value ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-sm-12">
                    <label class="form-label" for="shift_id">Holiday Name</label>
                    <div class="input-group input-group-merge">
                      <span id="holiday_name" class="input-group-text"><i class="ti ti-id"></i></span>
                      <input type="text" id="holiday-name" class="form-control dt-holiday-name" name="name" />
                    </div>
                  </div>
                <div class="col-sm-12">
                  <label class="form-label" for="shift_start_time">Holiday Date</label>
                  <div class="input-group input-group-merge">
                    <span id="holiday_date" class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="date" id="holiday-date" class="form-control dt-holiday-date" name="date" />
                  </div>
                </div>

                <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                  <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
              </form>
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
        offCanvasElement = document.querySelector('#add-new-holiday');

      // To open offCanvas, to add new record
      if (newRecord) {
        newRecord.addEventListener('click', function () {
          offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
          // Empty fields on offCanvas open

          (offCanvasElement.querySelector('.dt-holiday-name').value = ''),
          (offCanvasElement.querySelector('.dt-holiday-date').value = ''),

          // Open offCanvas with form
          offCanvasEl.show();
        });
      }
    }, 200);


var dtHolidayTable = $('.datatables-holidays');
// Users List datatable
if (dtHolidayTable.length) {
  var dtHoliday = dtHolidayTable.DataTable({
      ajax: {
          url: "/holiday/list",
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

      { data: 'name', title: 'Name of Holiday' },
      { data: 'date', title: 'Date of Holiday' },
      {
        data: 'group',
        title: 'Holiday Group',
            render: function(data, type, row) {
                switch (data) {
                case 1: return 'General';
                case 2: return 'Finance';
                case 3: return 'DIP';
                case 4: return 'Engineering';
                default: return 'Unknown';
                }
            }
        },
      {
        targets: 3,
        render: function(data, type, full, meta){
            let holiday_id = full['id'];
            $buttons = `<a href="javascript:;" class="text-body delete-holiday" data-id="${holiday_id}"><i class="ti ti-trash ti-sm mx-2"></i></a>`;
            return $buttons;
        }
      },

    ],

      dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [

        {
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Holiday</span>',
          className: 'create-new btn btn-primary'
        }
      ]


});
$('div.head-label').html('<h5 class="card-title mb-0">Holidays</h5>');
}



        // delete holiday

            window.onload = function () {

            document.querySelectorAll(".delete-holiday").forEach((element) => {
                element.addEventListener("click", function () {
                    let holidayId = this.getAttribute("data-id"); // Corrected

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
                            fetch(`/holiday-delete/${holidayId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                    "Content-Type": "application/json"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Deleted!", "Holiday has been deleted.", "success").then(() => {
                                        location.reload(); // Reload page after deletion
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
                });
            });

            }





});



</script>
@stop



