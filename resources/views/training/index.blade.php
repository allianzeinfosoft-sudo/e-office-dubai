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
                                        <th>Training Details</th>
                                        <th>Document</th>
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
                    { data: 'trainings_detatils', title: 'Training Details'},
                    { data: 'document', title: 'Document',
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="/storage/trainings/${data}" target="_blank" class="btn btn-sm btn-info">View Document</a>`;
                            } else {
                                return 'No Document';
                            }
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('trainings.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-trainings" onclick="openTrainingsOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-trainings" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
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

</script>
@endpush
