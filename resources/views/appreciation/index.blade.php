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
    <div class="layout-container">
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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openAppreciationOffcanvas()">

                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-basic datatables-appreciation table border-top table-stripedc hover_effect" id="datatables-appreciation">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th>Display Date</th>
                                        <th>Create Date</th>
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="appreciation_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Appreciation</h5>
                <span class="text-white slogan">Create New Appreciation</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-appreciation-form />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    // form validation
    var quillAppreciation, quillAppreciation1 = new Quill('#appreciation-editor', { theme: 'snow',
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
        const appreciationForm = document.getElementById('appreciation-form');
        appreciationForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values

            const appreciant = document.getElementById('appreciant').value.trim();
            const display_date = document.getElementById('display_date').value.trim();
            const picture = document.querySelector('input[name="picture"]:checked');
            const appreciation_details = quillAppreciation1.root.innerText.trim();
            const hiddenAppreciation_details = document.getElementById('appreciation_details');

            hiddenAppreciation_details.value = appreciation_details;
            let errors = [];

            // === Validation ===
            if (!appreciant) {
                errors.push("Appreciant is required.");
            }

            if (!display_date) {
                errors.push("Display date is required.");
            } else if (isNaN(Date.parse(display_date))) {
                errors.push("Display date must be a valid date.");
            }

            if (!picture) {
                errors.push("Picture is required.");
            }

            if (!appreciation_details) {
                errors.push("Appreciation details reason is required");
            }

            // === Show errors or submit ===
            let errorBox = document.getElementById('formErrors');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'formErrors';
                errorBox.className = 'alert alert-danger mt-3';
                appreciationForm.prepend(errorBox);
            }

            if (errors.length > 0) {
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else {
                errorBox.innerHTML = ''; // Clear old errors
                appreciationForm.submit(); // Submit manually only if no errors
            }
        });
    });


    $(function() {
        var thoughtsTable = $('.datatables-appreciation'),
        select2 = $('.select2');
        if (thoughtsTable.length) {

            thoughtsTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('appreciation.index') }}", // Fixed syntax
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
                    {
                        data: 'picture',
                        title: 'Image',
                        render: function (data, type, row) {
                            if (data) {
                                return `<img src="/storage/appreciation_flowers/${data}" alt="Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 6px;" />`;
                            } else {
                                return 'No Image';
                            }
                        }
                    },
                    { data: 'appreciant', title: 'Title' },
                    { data: 'appreciation_details', title: 'Details' },
                    { data: 'display_date', title: 'Display Date' },
                    { data: 'created_at', title: 'Create Date'},
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('appreciation.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-appreciation datatable_btn" onclick="openAppreciationOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-appreciation datatable_btn" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    /*delete thoughts function*/

    $(document).on('click', '.delete-appreciation', function(e) {
        e.preventDefault();
        const appreciationId = $(this).data('id');

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
                        url: `/appreciation/${appreciationId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Appreciation has been deleted.", "success").then(() => {
                                $('#datatables-appreciation').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


  function openAppreciationOffcanvas(targetId = null) {
    const form = document.getElementById('appreciation-form');
    if (form) form.reset(); // Safely reset form

    $('#target_id').val(''); // Clear hidden ID

    if (targetId) {
        $('#offcanvas-title-container').html(`
            <h5 class="offcanvas-title text-white" id="staticBackdropLabel">Edit Appreciation</h5>
            <span class="text-white slogan">Edit New Appreciation</span>
        `);

        $.ajax({
            url: `/appreciation/${targetId}/edit`,
            type: 'GET',
            success: function (data) {
                let appreciation = data.appreciation;

                let cleanContent = appreciation.appreciation_details?.replace(/^<p>|<\/p>$/g, '') || '';

                $('#target_id').val(appreciation.id);
                $('#appreciant').val(appreciation.appreciant);
                $('#display_date').val(appreciation.display_date);
                $('#appreciation_details').val(cleanContent);
                $('#picture').val([appreciation.picture]);
                $('input[name="picture"][value="' + appreciation.picture + '"]').prop('checked', true);
                if (typeof quillAppreciation1 !== 'undefined') {
                    quillAppreciation1.root.innerHTML = cleanContent;
                }
            }
        });
    }

    var offcanvasElement = $('#appreciation_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}


</script>
@endpush
