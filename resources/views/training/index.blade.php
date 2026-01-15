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
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openTrainingsOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add Training</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-trainings table border-top table-stripedc" id="datatables-trainings">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Training Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        {{-- <th>Training Details</th> --}}
                                        <th>Document</th>
                                        <th>Training Status</th>
                                        <th>Status</th>
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
            </div>
        </div>
    </div>
</div>

<!-- Add Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="trainings_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Training</h5>
                <span class="text-white slogan">Create New Training</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-training-form action="{{ route('trainings.store') }}" />
            </div>
        </div>
    </div>
</div>

{{-- model box --}}
<div class="modal fade" id="viewTrainingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Training Details</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <h6 class="mb-2">Training Information</h6>
                <table class="table table-bordered">
                    <tr><th>Title</th><td id="vt_title"></td></tr>
                    <tr><th>Start Date</th><td id="vt_start"></td></tr>
                    <tr><th>End Date</th><td id="vt_end"></td></tr>
                    <tr><th>Description</th><td id="vt_details"></td></tr>
                    <tr><th>Documents</th><td id="vt_document"></td></tr>
                    <tr><th>Status</th><td id="vt_status"></td></tr>
                </table>

                <h6 class="mt-4 mb-2">Assigned Users</h6>
                @if(auth()->user()->hasAnyRole(['HR','Developer','G1']))
                    <button class="btn btn-sm btn-primary ms-auto " id="openAddUserModal">
                        <i class="ti ti-user-plus"></i> Add User
                    </button>
                @endif
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Email</th>
                            <th>Acceptance Status</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody id="vt_users"></tbody>
                </table>

            </div>
        </div>
    </div>
</div>

{{-- user add box --}}

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Assign Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="assign_training_id">

                <label class="mb-1">Select Users</label>
                <select id="assign_users"
                        class="select2 form-select"
                        multiple
                        style="width:100%">
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="assignUsersBtn">
                    <i class="ti ti-check"></i> Assign
                </button>
            </div>

        </div>
    </div>
</div>

@stop


@push('js')
<script>
    // form validation
    var quillEditor, quillEditor1 = new Quill('#trainings-editor', { theme: 'snow',
        placeholder: 'Type your reason here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            } });


    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('trainings-form');


        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values

            const trainings_title = document.getElementById('trainings_title').value.trim();
            const department = document.getElementById('department').value.trim();
            const start_date_time = document.getElementById('start_date_time').value.trim();
            const end_date_time = document.getElementById('end_date_time').value.trim();
            const training_document = document.getElementById('document').value.trim();
            const trainings_details = quillEditor1.root.innerText.trim();
            const hiddenTrainings_details = document.getElementById('trainings_details');

            hiddenTrainings_details.value = trainings_details;

            let errors = [];

            // === Validation ===
            if (!trainings_title) {
                errors.push("Trainings Title is required.");
            }

            if (!department) {
                errors.push("Department is required.");
            }

            if (!start_date_time) {
                errors.push("Start date time is required.");
            } else if (isNaN(Date.parse(start_date_time))) {
                errors.push("Start date time must be a valid date.");
            }

            if (!end_date_time) {
                errors.push("End date time is required.");
            } else if (isNaN(Date.parse(end_date_time))) {
                errors.push("End date time must be a valid date.");
            }

            if (!hiddenTrainings_details) {
                errors.push("Trainings details reason is required");
            }

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
    });


    $(function() {
        const currentUserRoles = @json(Auth::user()->getRoleNames());
        var trainingsTable = $('.datatables-trainings'),
        select2 = $('.select2');
        if (trainingsTable.length) {

            trainingsTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('trainings.index') }}", // Fixed syntax
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: null,
                        title: 'S.No',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false, // Optional: prevent sorting on this column
                        searchable: false // Optional: exclude from search
                    },
                    { data: 'trainings_title', title: 'Training Title' },
                    { data: 'trainings_startdate', title: 'Start Date' },
                    { data: 'trainings_enddate', title: 'End Date' },
                    // { data: 'trainings_details', title: 'Training Details'},
                    {
                        data: 'document',
                        title: 'Document',
                        render: function (data, type, row) {
                            
                            let isAdmin = false;
                            // Restrict access unless accepted or user is admin
                            if (row.acceptance_status !== 'accepted' && !isAdmin) {
                                return 'Please accept to view document';
                            }
                            
                            if (data) {
                                return `<a href="/storage/trainings/${data}" target="_blank" class="btn btn-sm btn-info">View Document</a>`;
                            }

                            // If no document found
                            return 'No Document';
                        }
                    },
                    {
                        data: 'training_status',
                        title: 'Training Status',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {

                            if (data === 'start_soon') {
                                return `
                                    <span class="badge bg-warning">
                                        <i class="ti ti-clock"></i> Starting Soon
                                    </span>`;
                            }

                            if (data === 'ongoing') {
                                return `
                                    <span class="badge bg-success">
                                        <i class="ti ti-player-play"></i> Ongoing
                                    </span>`;
                            }

                            if (data === 'ended') {
                                return `
                                    <span class="badge bg-danger">
                                        <i class="ti ti-circle-x"></i> Ended
                                    </span>`;
                            }

                            return `<span class="badge bg-secondary">N/A</span>`;
                        }
                    },

                    {data: 'acceptance_status', title: 'Status'},
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {

                            let isAdmin = false;
                            if (typeof currentUserRoles !== 'undefined') {
                                isAdmin = currentUserRoles.includes("HR") ||
                                        currentUserRoles.includes("Developer") ||
                                        currentUserRoles.includes("G1");
                            }

                            let buttons = '';

                            // ❌ HIDE accept/reject if training is ENDED
                            if (row.training_status !== 'ended') {

                                if (isAdmin && row.acceptance_status == 'Not Assigned') {
                                    // nothing for admin if not assigned
                                } else {

                                    // Accept button
                                    if (row.acceptance_status !== 'accepted') {
                                        buttons += `
                                            <button class="btn btn-sm btn-success me-2 open-modal accept-training"
                                                    data-bs-toggle="modal"
                                                    data-id="${row.id}"
                                                    data-status="accepted"
                                                    data-bs-target="#addNewCCModal">
                                                <i class="fa fa-check-circle"></i>
                                            </button>`;
                                    }

                                    // Reject button
                                    if (row.acceptance_status !== 'rejected') {
                                        buttons += `
                                            <button class="btn btn-sm btn-danger open-modal reject-training"
                                                    data-bs-toggle="modal"
                                                    data-id="${row.id}"
                                                    data-status="rejected"
                                                    data-bs-target="#addNewCCModal">
                                                <i class="fa fa-times-circle"></i>
                                            </button>`;
                                    }
                                }
                            }

                            // Admin-only buttons (always visible for admin)
                            let adminButtons = '';

                            if (isAdmin) {
                                adminButtons = `
                                    <a href="javascript:void(0)"
                                    class="btn btn-sm btn-icon btn-primary edit-trainings"
                                    onclick="openTrainingsOffcanvas(${row.id})">
                                    <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)"
                                    class="btn btn-sm btn-icon btn-danger delete-trainings"
                                    data-id="${row.id}">
                                    <i class="ti ti-trash"></i>
                                    </a>`;
                            }

                            // View button → always visible
                            let viewButton = '';
                            if(row.acceptance_status !== 'accepted' && !isAdmin){
                                viewButton = '';
                            }else{
                                viewButton = `
                                    <button class="btn btn-sm btn-icon btn-warning me-1 view-training"
                                            data-id="${row.id}">
                                        <i class="ti ti-eye"></i>
                                    </button>`;
                            }
                            
                            return `
                                <div class="align-items-center">
                                    ${viewButton}
                                    ${adminButtons}
                                    ${buttons}
                                </div>`;
                        }
                    }


                ]
            });
        }
    });

    /*delete trainings function*/

    $(document).on('click', '.delete-trainings', function(e) {
        e.preventDefault();
        const trainingId = $(this).data('id');

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
                        url: `/trainings/${trainingId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Training has been deleted.", "success").then(() => {
                                $('#datatables-trainings').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openTrainingsOffcanvas(targetId = null) {
    $('#trainings-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Trainings</h5><span class="text-white slogan">Edit New Trainings</span>`);
        $.ajax({
            url: `/trainings/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                const training = data.training;

                // Fill inputs
                $('#target_id').val(training.id);
                $('#trainings_title').val(training.training_title);
                $('#department').val(training.department_id);
                $('#start_date_time').val(training.start_date_time);
                $('#end_date_time').val(training.end_date_time);

                 // Disable employee field in edit mode
                if ($('#target_id').val()) {
                    $('#employee').prop('disabled', true);
                }

                // Details -> Quill Editor
                let cleanContent = training.training_details.replace(/^<p>|<\/p>$/g, '');
                quillEditor1.root.innerHTML = cleanContent;

                // Employees (multiselect)
                $('#employee').val(data.selected_employees).trigger('change');

                // File Preview
                if (training.document) {
                    const previewEdit = document.getElementById("PicturePreview");
                    previewEdit.src = `/storage/${training.document}`;
                    previewEdit.style.display = "block";
                }

                $('#document').val('');
            }
        });
    }
    var offcanvasElement = $('#trainings_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}


$(document).on('click', '.accept-training, .reject-training', function () {

    let trainingId = $(this).data('id');
    let status     = $(this).data('status');

    $.ajax({
        url: "{{ route('trainings.acceptance') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            training_id: trainingId,
            status: status
        },
        success: function (response) {
            toastr.success(response.message);
            $('.datatables-trainings').DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong');
        }
    });
});


// view model box
let currentViewTrainingId = null;
$(document).on('click', '.view-training', function () {
    currentViewTrainingId = $(this).data('id');

    $.get(`/trainings/${currentViewTrainingId}/view`, function (res) {

        let statusHtml = '';
        if (res.training_status === 'start_soon') {
            statusHtml = '<span class="badge bg-warning"><i class="ti ti-clock"></i>Starting Soon</span>';
        }
        else if (res.training_status === 'ongoing') {
            statusHtml = '<span class="badge bg-success"><i class="ti ti-player-play"></i>Training Ongoing</span>';
        }
        else if (res.training_status === 'ended') {
            statusHtml = '<span class="badge bg-danger"><i class="ti ti-circle-x"></i>Training Ended</span>';
        }
        $('#vt_title').text(res.title);
        $('#vt_start').text(res.start);
        $('#vt_end').text(res.end);
        $('#vt_details').text(res.details);
        $('#vt_document').html(
            res.document
                ? `<a href="/storage/trainings/${res.document}" target="_blank" class="btn btn-sm btn-info">View Document</a>`
                : 'No Document'
        );
        $('#vt_status').html(statusHtml);


        let rows = '';
        res.users.forEach((u, i) => {
                let acceptBadge = 'bg-warning';
                if (u.acceptance_status === 'accepted') acceptBadge = 'bg-success';
                if (u.acceptance_status === 'rejected') acceptBadge = 'bg-danger';

                let attendanceHtml = '<span class="text-muted">N/A</span>';

                // ✅ Only Admin + Accepted users can mark attendance
                if (u.can_mark_attendance) {

                    let checked = u.attendance_status === 'present' ? 'checked' : '';

                    attendanceHtml = `
                        <div class="form-check">
                            <input class="form-check-input mark-attendance"
                                type="checkbox"
                                data-id="${u.id}"
                                ${checked}>
                            <label class="form-check-label">
                                Present
                            </label>
                        </div>`;
                }
                // Read-only display
                else if (u.attendance_status) {

                        if (u.attendance_status === 'present') {
                            attendanceHtml = `
                                <span class="badge bg-success">
                                    ${u.attendance_status}
                                </span>`;
                        }
                        else if (u.attendance_status === 'absent') {
                            attendanceHtml = `
                                <span class="badge bg-danger">
                                    ${u.attendance_status}
                                </span>`;
                        }
                    }


                rows += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${u.name}</td>
                        <td>${u.email}</td>
                        <td>
                            <span class="badge ${acceptBadge}">
                                ${u.acceptance_status}
                            </span>
                        </td>
                        <td>${attendanceHtml}</td>
                    </tr>`;
            });


        $('#vt_users').html(rows);
        $('#viewTrainingModal').modal('show');
    });
});

// add user model

$('#openAddUserModal').on('click', function () {

     if (!currentViewTrainingId) {
        toastr.error('Training not selected');
        return;
    }

    $('#assign_training_id').val(currentViewTrainingId);

    $.get(`/trainings/${currentViewTrainingId}/available-users`, function (res) {

        let options = '';
        res.users.forEach(u => {
            options += `<option value="${u.user_id}">${u.full_name}</option>`;
        });

        $('#assign_users')
            .html(options)
            .select2({
                dropdownParent: $('#addUserModal')
            });

        $('#addUserModal').modal('show');
    });
});


// submit assigned users
$('#assignUsersBtn').on('click', function () {

    let trainingId = $('#assign_training_id').val();
    let users = $('#assign_users').val();

    if (!users || users.length === 0) {
        toastr.warning('Please select at least one user');
        return;
    }

    $.ajax({
        url: "{{ route('trainings.assign-users') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            training_id: trainingId,
            users: users
        },
        success: function (res) {
            toastr.success(res.message);
            $('#addUserModal').modal('hide');

            // Reload view modal users
            $('.view-training[data-id="' + trainingId + '"]').click();
        },
        error: function () {
            toastr.error('Unable to assign users');
        }
    });
});




// mark attendance
$(document).on('change', '.mark-attendance', function () {

    let id = $(this).data('id');
    let status = $(this).is(':checked') ? 'present' : 'absent';

    $.ajax({
        url: "{{ route('training.attendance') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            attendance_status: status
        },
        success: function () {
            toastr.success('Attendance updated');
        },
        error: function () {
            toastr.error('Unable to update attendance');
        }
    });
});

</script>
@endpush
