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
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">    
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Settings /</span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div class=" float-end mt-15 mr-20">
                            </div>

                            <table class="datatables-basic datatables-custom-markout table border-top table-stripedc table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th><i class="ti ti-users"></i></th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Markin Date</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>  
                    </div>

                </div>

                <!-- Footer -->
                <x-footer /> 
                <!-- / Footer -->
                 
                <div class="content-backdrop fade"></div>

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>

                <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="custom_markout_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-logout fs-2 text-white"></i> 
            <span class="">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Custom Markout</h5>
                <span class="text-white slogan">Custom markout</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="row" id="customMarkOutForm" action="" method="post">
                    @csrf
                    <div class="col-12 mb-3 bg-light">
                        <div class="d-flex align-items-center justify-content-between gap-2">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <img class="img-fluid rounded mb-3 pt-1 mt-4" id="profileImage" src="../../assets/img/avatars/15.png" height="100" width="100" alt="User avatar">
                                <div class="user-info text-center">
                                    <h4 class="mb-2">Violet Mendoza</h4>
                                    <span class="badge bg-label-secondary mt-1">Author</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <h1> <i class="ti ti-calendar text-success display-1"></i></h1>
                                <span>
                                    <h4 id="loggedInDate">{{ date('d-m-Y'); }}</h4>
                                    <h5 id="loggedInTime">2362</h5>
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="col-4 mb-3">
                        <label for="signout_time" class="form-label">Mark-out Time <span class="text-danger">*</span></label>
                        <input class="form-control" type="time" id="signout_time" name="signout_time" step="1" value=""  placeholder="Time" />
                        <input type="hidden" id="signout_date" name="signout_date"  value="" />
                        <input type="hidden" id="attendance_id" name="attendance_id"  value="" />
                        <input type="hidden" id="signout_late_note" name="signout_late_note"  value="Admin side logout" />
                    </div>

                    <div class="col-12 mb-3">
                        <button class="btn btn-primary w-100" onclick="updateCustomMarkout()" type="button"><i class="ti ti-logout"></i> Mark Out</button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function(){

        var customMarkoutTable = $('.datatables-custom-markout'),
            select2 = $('.select2');

        if (customMarkoutTable.length) {            
            customMarkoutTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('attendance.marked-in-list') }}", // Fixed syntax
                    dataType: "json", 
                    dataSrc: "data"  
                },
                columns: [
                    { 
                        data: null,
                        title: 'Sl. No',
                        render: function (data, type, row) {
                            return customMarkoutTable.DataTable().page.info().start + 1;
                        }
                    },
                    { data: 'profile_image', title: '<i class="ti ti-users"></i>' },
                    { data: 'name', title: 'Name' },
                    { data: 'username', title: 'Username' },
                    { data: 'markin_date', title: 'Markin Date' },
                    { data: 'markin_time', title: 'Time' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            const editUrl = "{{ route('project.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" onclick="customMarkOut(${row.id})" class="btn btn-sm btn-primary edit-project"><i class="ti ti-logout"></i> Mark Out</a>
                                <button type="button" class="btn btn-sm btn-danger delete-project" onclick="deleteMarkin(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i> Delete</button>
                            `;
                        }
                    }
                ]
            });
        }
       
        $('#start_date,  #end_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        
    });

     function customMarkOut(id) {
        var url = "{{ route('attendance.emplyee-markin', ':id') }}".replace(':id', id);
        var offcanvasElement = document.getElementById('custom_markout_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var data = response.data;
                    var storagePath = data.employee.profile_image ? "/storage/" + data.employee.profile_image : '/assets/img/avatars/1.png';

                    // Fill user profile image
                    $('#profileImage').attr('src', storagePath);

                    // Fill user name
                    $('#customMarkOutForm h4.mb-2').text(data.employee.full_name);

                    // Fill role or badge (optional)
                    $('#customMarkOutForm .badge').text(data.employee.designation ?? 'Employee');

                    $('#loggedInDate').text(data.signin_date);
                    $('#signout_date').val(data.signin_date);
                    $('#loggedInTime').text(data.signin_time);
                    $('#attendance_id').val(data.id);

                } else {
                    alert('Failed to fetch employee data.');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('Error fetching data.');
            }
        });
    }
 
function updateCustomMarkout() {
    var attendanceId = $('#attendance_id').val();
    var url = "{{ route('attendance.custom-mark-out', ':id') }}".replace(':id', attendanceId);
    var formData = $('#customMarkOutForm').serialize();

    $.ajax({
        type: "POST", // or "PUT" if required
        url: url,
        data: formData,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function (response) {
            if (response.success) {
                $('.datatables-custom-markout').DataTable().ajax.reload();
                // Optionally close the offcanvas or show success toast
                var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('custom_markout_offcanvas'));
                if (offcanvas) offcanvas.hide();
                alert('Mark-out updated successfully!');
            } else {
                alert(response.message || 'Update failed.');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            alert('Something went wrong.');
        }
    });
}

function deleteMarkin(id){
    var url = "{{ route('attendance.destroy', ':id') }}".replace(':id', id);

    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.success) {
                    alert('Deleted successfully!');
                    $('.datatables-custom-markout').DataTable().ajax.reload(); // optional
                } else {
                    alert(response.message || 'Delete failed.');
                }
            },
            error: function (xhr) {
                console.error(xhr);
                alert('Something went wrong.');
            }
        });
    }
}
    
</script>
@endpush