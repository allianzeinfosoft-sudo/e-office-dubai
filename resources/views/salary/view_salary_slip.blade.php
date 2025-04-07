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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}

            </div>
        @endif

        <div class="content-wrapper">
          <!-- Content -->


          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Salary Slip</h4>
          <div class="card mb-4">
            <form id="SalaryForm" class="card-body" method="get" action="{{ route('fetch.salarySlip') }}">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                <div class="row g-3">
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <label for="select2Basic" class="form-label">Select Username:</label>
                            <select id="username" name="username" class="select2 form-select form-select-lg" data-allow-clear="true">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id ?? '' }}" {{ (old('username') == $user->id) ? 'selected' : '' }}>{{ $user->employee->full_name ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            @php
                                use App\Helpers\CustomHelper;
                                $helper = new CustomHelper();
                            @endphp
                            <label for="select2Basic" class="form-label">Select Month:</label>

                            <select id="select2Month" name="select2Month" class="select2 form-select form-select-lg" data-allow-clear="true">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}" {{ old('select2Month') == $month ? 'selected' : '' }}>
                                        {{  $helper->getMonthNames($month) }}  {{-- Ensure the function name is correct --}}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="select2Basic" class="form-label">Select Year:</label>
                            <select id="select2Year" name="select2Year" class="select2 form-select form-select-lg" data-allow-clear="true">
                            @for ($year = 2000 ; $year <= 2050 ; $year++)
                                <option value="{{ $year }}" {{ (old('select2Year') == $year) ? 'selected' : '' }}>{{ $year ?? '' }}</option>
                            @endfor
                            </select>
                        </div>
                    </div>
              </div>
              <div class="pt-4">
                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              </div>
            </form>
          </div>

          <div class="card">
            <div class="card-datatable table-responsive pt-0">
              <table class="salary-slip-table table">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>EmployeeName</th>
                    <th>Department</th>
                    <th>PF No</th>
                    <th>Created Date</th>
                    <th>Salary Month</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>



          <!-- Modal to add new record -->
          <div class="offcanvas offcanvas-end  w-45" id="upload-salary">
            <div class="offcanvas-header border-bottom">
              <h5 class="offcanvas-title" id="exampleModalLabel">Upload Salary Slip</h5>
                <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
            </div>
            <div class="offcanvas-body flex-grow-1">

                <div class="col-sm-12">
                  <div class="input-group input-group-merge">

                    <div class="col-12">
                        <div class="card mb-4">
                          <div class="card-body">
                            <form id="uploadForm" method="POST" class="form-control" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload Salary File</label>
                                    <input class="form-control" type="file" id="formFile" name="file[]" webkitdirectory directory multiple required/>
                                  </div>

                              <div class="col-sm-12">
                                <button type="button" id="startUpload" class="btn btn-success">Start</button>
                                <button type="button" id="CloseUpload" class="btn btn-danger">Close</button>
                              </div>
                            </form>
                          </div>
                        </div>
                            <!-- Bordered Table -->
                            <div class="card mt-4">
                                <h5 class="card-header">Upload Status</h5>
                                <div class="card-body">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Employee ID</th>
                                                    <th>Upload Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="statusTable">
                                                <!-- Status updates will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Bordered Table -->
                      </div>
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

  var dataTableSalarySlip = $('.salary-slip-table'),
    dt_permission;
  // Users List datatable
  if (dataTableSalarySlip.length) {
    dt_salary_slip = dataTableSalarySlip.DataTable({
    // ajax: assetsPath + 'json/permissions-list.json', // JSON file to add data

    ajax: {
          url: "/fetch/salarySlip",
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
        { data: 'full_name', title: 'EmployeeName' },
        { data: 'department', title: 'Department' },
        { data: 'pf_no', title: 'PF No'},
        { data: 'created_date', title: 'Created Date'},
        { data: 'salary_slip_month', title: 'Salary Month' },
        {

            data: 'salary_slip',
            title: 'Download Slip',
            render: function (data, type, row) {
                if (data) {
                    return `<a href="/storage/salary_slips/${data}" target="_blank" class="btn btn-sm btn-primary">View</a>`;
                }
                return "N/A";
            }

        }
      ],

      dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Salary Slip</span>',
          className: 'create-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#upload-salary'
          }
        }
      ]

    });

    $('div.head-label').html('<h5 class="card-title mb-0">Salary Slip List</h5>');
  }

});




(function () {
  // previewTemplate: Updated Dropzone default previewTemplate
  // ! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
                            <div class="dz-details">
                            <div class="dz-thumbnail">
                                <img data-dz-thumbnail>
                                <span class="dz-nopreview">No preview</span>
                                <div class="dz-success-mark"></div>
                                <div class="dz-error-mark"></div>
                                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                <div class="progress">
                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                                </div>
                            </div>
                            <div class="dz-filename" data-dz-name></div>
                            <div class="dz-size" data-dz-size></div>
                            </div>
                            </div>`;


                // --------------------------------------------------------------------
                const dropzoneBasic = document.querySelector('#dropzone-basic');
                if (dropzoneBasic) {
                    const myDropzone = new Dropzone(dropzoneBasic, {
                    previewTemplate: previewTemplate,
                    parallelUploads: 1,
                    maxFilesize: 5,
                    addRemoveLinks: true,
                    maxFiles: 1
                    });
                }


})();

/* upload zip file */

$(document).ready(function () {
    $("#startUpload").click(function (e) {
        e.preventDefault();

        let files = $("#formFile")[0].files;

        if (!files.length) {
            alert("Please select a folder with files.");
            return;
        }

        $("#statusTable").html(""); // Clear previous status

        Array.from(files).forEach((file, index) => {
            let formData = new FormData();
            formData.append("file", file);
            formData.append("_token", "{{ csrf_token() }}");

            let newRow = `<tr id="row-${index}">
                <td>${index + 1}</td>
                <td>${file.name}</td>
                <td class="status">Uploading...</td>
            </tr>`;
            $("#statusTable").append(newRow);

            $.ajax({
                url: "{{ route('upload.salary.file') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    let statusCell = $(`#row-${index} .status`);
                    if (response.status === "success") {
                        statusCell.html(`<span class="text-success">${response.message}</span>`);
                    } else {
                        statusCell.html(`<span class="text-danger">${response.message}</span>`);
                    }
                },
                error: function () {
                    $(`#row-${index} .status`).html(`<span class="text-danger">Upload error</span>`);
                }
            });
        });
    });

    $("#CloseUpload").click(function () {
        location.reload();
    });

});

</script>
@stop
