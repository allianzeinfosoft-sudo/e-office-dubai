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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openTrainingTestOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add Training Test</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-test-trainings table border-top table-stripedc" id="datatables-test-trainings">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Test Title</th>
                                        <th>Training Title</th>
                                        <th>Date</th>
                                        <th>Assign Status</th>
                                        <th>Attempt Status</th>
                                        <th>Score</th>
                                        <th>Result</th>
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="trainings_test_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Training Test</h5>
                <span class="text-white slogan">Create New Training</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-training-test-form action="{{ route('training-tests.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop

<!-- View Training Test Question Paper Modal -->
<div class="modal fade" id="viewTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="ti ti-file-text"></i> Training Test Question Paper
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <h5 id="vt_test_title"></h5>
                <p class="text-muted">
                    Total Marks: <strong id="vt_total_marks"></strong>
                </p>
                <hr>

                <div id="vt_questions"></div>
            </div>

        </div>
    </div>
</div>

@push('js')
<script>
    // form validation
    var quillEditor, quillEditor1 = new Quill('#training-test-editor', { theme: 'snow',
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
        const form = document.getElementById('trainings-test-form');


        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values

            const trainings_title = document.getElementById('trainings_title').value.trim();
            const start_date_time = document.getElementById('start_date_time').value.trim();
            const end_date_time = document.getElementById('end_date_time').value.trim();
            const trainings_details = quillEditor1.root.innerText.trim();
            const hiddenTrainings_details = document.getElementById('training_test_details');

            hiddenTrainings_details.value = trainings_details;

            let errors = [];

            // === Validation ===
            if (!trainings_title) {
                errors.push("Trainings Title is required.");
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
        var trainingsTable = $('.datatables-test-trainings'),
        select2 = $('.select2');
        if (trainingsTable.length) {

            trainingsTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('training-tests.index') }}", // Fixed syntax
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
                    { data: 'training_title', title: 'Training Title' },
                    { data: 'title', title: 'Test Title' },
                    { data: 'start_date', title: 'Date' },
                    { data: 'assign_status', title: 'Assign Status' },
                    { data: 'attempt_status', title: 'Attempt Status' },
                    { data: 'score', title: 'Score' },
                    { data: 'result', title: 'Result' },
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

                            // Admin-only buttons
                            let adminButtons = '';
                            if (isAdmin) {
                                adminButtons = `
                                    <a href="javascript:void(0)"
                                    class="btn btn-sm btn-icon btn-primary edit-training-test"
                                    onclick="openTrainingTestOffcanvas(${row.id})" title="Edit Training Test">
                                    <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)"
                                    class="btn btn-sm btn-icon btn-danger delete-training-test"
                                    data-id="${row.id}" title="Delete Training Test">
                                    <i class="ti ti-trash"></i>
                                    </a>`;
                            }

                            // VIEW button → common for all users
                            let viewButton = `
                                <button class="btn btn-sm btn-icon btn-warning me-1 view-training-test"
                                        data-id="${row.id}" title="View Test Question Paper">
                                    <i class="ti ti-eye"></i>
                                </button>`;

                            let attendButton = '';
                                if (row.attempt_status === 'submitted') {
                                    attendButton = `
                                        <a href="/training-tests/${row.id}/attend"
                                        class="btn btn-sm btn-icon btn-success me-1"
                                        title="Attend Test" title="Download Test Report">
                                            <i class="ti ti-download"></i>
                                        </a>`;
                                }else {
                                      attendButton = `
                                        <a href="/training-tests/${row.id}/attend"
                                        class="btn btn-sm btn-icon btn-success me-1"
                                        title="Attend Test" title="Attend Test">
                                            <i class="ti ti-clipboard-check"></i>
                                        </a>`;
                                }


                            return `
                                <div class="align-items-center">
                                    ${viewButton}
                                    ${attendButton}
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

    $(document).on('click', '.delete-training-test', function(e) {
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
                        url: `/training-tests/${trainingId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Training test has been deleted.", "success").then(() => {
                                $('#datatables-test-trainings').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openTrainingTestOffcanvas(targetId = null) {
    $('#training-test-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Training Test</h5><span class="text-white slogan">Edit New Training Tests</span>`);
        $.ajax({
            url: `/training-tests/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                const training = data.training;

                // Fill inputs
                $('#target_id').val(training.id);
                $('#trainings_title').val(training.training_title);
                $('#start_date_time').val(training.start_date_time);
                $('#end_date_time').val(training.end_date_time);

                // Details -> Quill Editor
                let cleanContent = training.training_details.replace(/^<p>|<\/p>$/g, '');
                quillEditor1.root.innerHTML = cleanContent;

            }
        });
    }
    var offcanvasElement = $('#trainings_test_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}


// View Training Test Question Paper
$(document).on('click', '.view-training-test', function () {

    let testId = $(this).data('id');

    $.get(`/training-tests/${testId}/question-paper`, function (res) {

        $('#vt_test_title').text(res.title);
        $('#vt_total_marks').text(res.total_marks);

        let html = '';

        res.questions.forEach((q, index) => {

            html += `
                <div class="mb-4">
                    <strong>Q${index + 1}. ${q.question}</strong>
                    <ul class="mt-2">
                        <li>A. ${q.option_a}</li>
                        <li>B. ${q.option_b}</li>
                        <li>C. ${q.option_c}</li>
                        <li>D. ${q.option_d}</li>
                    </ul>
                    <span class="badge bg-info">Marks: ${q.marks}</span>
                </div>
                <hr>`;
        });

        $('#vt_questions').html(html);
        $('#viewTestModal').modal('show');
    });
});




</script>
@endpush
