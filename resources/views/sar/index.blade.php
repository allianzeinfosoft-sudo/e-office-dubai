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

    .modal-content {
        border-radius: 15px;
        border: 2px solid #ff5f10;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background-color: #fdfdfd;
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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openSarTemplateOffcanvas()">

                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New Template</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-sar-template table border-top table-stripedc" id="datatables-sar-template">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Template Name</th>
                                        <th>Department</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
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

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="sar_template_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add SAR Template</h5>
                <span class="text-white slogan">Create New SAR Template</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-sar-template-form/>
            </div>
        </div>
    </div>
</div>


<!-- question view mode -->
   <div class="modal fade " id="sar_question_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-4">
            <div class="modal-header-custom">

                <h3 class="address-title">SAR Question Template</h3>
                <p class="  address-subtitle">Department</p>
            </div>
            <div class="modal-body">
                <div id="questionContainer" class="col-12">
                    <!-- Questions will be injected here -->
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                    Close
                </button>

            </div>
        </div>
    </div>
</div>

<!-- end question view model-->

@stop


@push('js')
<script>



    $(function() {
        var sarTemplateTable = $('.datatables-sar-template'),
        select2 = $('.select2');
        if (sarTemplateTable.length) {

            sarTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('sartemplate.index') }}", // Fixed syntax
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
                    { data: 'template_name', title: 'Template Name' },
                    { data: 'department', title: 'Department' },
                    { data: 'created_by', title: 'Created By' },
                    { data: 'created_date', title: 'Created Date'},
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('sartemplate.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary view-sar-template" title="view questions"  onclick="openSarQuestionOffcanvas(${row.id})""><i class="ti ti-eye"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-sar-template" title="edit" onclick="openSarTemplateOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-sar-template" title="delete" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    /*delete thoughts function*/

    $(document).on('click', '.delete-sar-template', function(e) {
        e.preventDefault();
        const sarTemplateId = $(this).data('id');

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
                        url: `/sartemplate/${sarTemplateId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                           Swal.fire("Deleted!", response.message || "PAR has been deleted.", "success").then(() => {
                                $('#datatables-sar-template').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function (xhr) {
                                let message = xhr.responseJSON?.message || "Something went wrong.";
                                Swal.fire("Error!", message, "error");
                            }

                        });

            }

    });
  });



  function openSarQuestionOffcanvas(sarTemplateId) {

    if (sarTemplateId) {
        $.ajax({
            url: `/sartemplate/${sarTemplateId}/fetch`,
            type: 'GET',
            success: function (data) {
                $('#modalAddressFirstName').val(data.template_name || '');

                // Show department name and creator
                $('.address-subtitle').text('Department: ' + (data.department ?? 'N/A') + ' | Created By: ' + (data.created_by ?? 'N/A'));

                let questionsHtml = '';
                if (data.questions && data.questions.length > 0) {
                    data.questions.forEach((q, index) => {
                        questionsHtml += `<div class="question-box">
                            <p class="question-title">Q${index + 1}: ${q.question}</p>`;
                        questionsHtml += `</div>`;
                    });
                } else {
                    questionsHtml = '<p>No questions found.</p>';
                }

                $('#questionContainer').html(questionsHtml);

                // Show modal (not offcanvas)
                const modal = new bootstrap.Modal(document.getElementById('sar_question_view'));
                modal.show();
            }
        });
    }
}




function openSarTemplateOffcanvas(targetId = null) {
    $('#sar-template-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID

    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Sar Template</h5><span class="text-white slogan">Edit Sar Template</span>`);
        $.ajax({
            url: `/sartemplate/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                $('#target_id').val(targetId);
                $('#template_name').val(data.name);
                $('#department_id').val(data.department).trigger('change');

                // Check if editing is allowed
                if (data.locked) {
                    $('#question-container').html(`<div class="alert alert-warning">This template is assigned to employees and cannot be edited.</div>`);
                    $('.btn-secondary').hide(); // hide add question button
                } else {
                    $('.btn-secondary').show();
                    $('#question-container').empty();
                    data.questions.forEach((q, i) => {
                        const html = `
                            <div class="question-block border rounded p-3 mb-3 position-relative" id="question-block-${questionIndex}">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="removeQuestion(${questionIndex})">
                                    Remove
                                </button>
                                <h5 class="question-title mb-2">Question ${questionIndex + 1}</h5>
                                <input type="text" name="questions[${questionIndex}][question]" class="form-control mb-2" required value="${q.question}">
                            </div>
                        `;
                        $('#question-container').append(html);
                        questionIndex++;
                    });
                }

            }
        });
    }
    var offcanvasElement = $('#sar_template_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

</script>
@endpush
