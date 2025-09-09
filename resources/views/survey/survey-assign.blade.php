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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openSurveyTemplateOffcanvas()">

                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Assign Survey</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-survey-assign table border-top table-stripedc" id="datatables-survey-assign">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Survey Name</th>
                                        <th>Description</th>
                                        <th>Department</th>
                                        <th>Employees</th>
                                        <th>Survey Start Date</th>
                                        <th>Survey End Date</th>
                                        <th>Created By</th>
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

<div class="offcanvas offcanvas-end w-25" data-bs-backdrop="static" tabindex="-1" id="survey_template_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Assign Survey</h5>
                <span class="text-white slogan">Assign Survey to Employees</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-survey-assign-form/>
            </div>
        </div>
    </div>
</div>


<!-- question view mode -->
   <div class="modal fade" id="survey_question_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-4">
            <div class="modal-header-custom">

                <h3 class="address-title">Survey Questions</h3>
                <p class="  address-subtitle">Department</p>
            </div>
            <div class="modal-body">
                <div id="questionContainer" class="col-12">
                    <!-- Questions will be injected here -->
                </div>
            </div>

            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                Close
            </button>
        </div>
    </div>
</div>

<!-- end question view model-->
<!-- view sar answer sheet -->
 <div class="modal fade" id="survey_answer_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-4">
            <div class="modal-header-custom">

                <h3 class="address-title">Survey Answer Sheet</h3>
                <p class="  address-subtitle">Department</p>
            </div>

                <div class="modal-body">
                    <div id="answerContainer" class="col-12">
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
<!--view sar question view model end -->

@stop


@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('survey-assign-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const department = document.getElementById('department')?.value.trim();
        const template = document.getElementById('template')?.value.trim();
        // const survey_name = document.getElementById('survey_name')?.value.trim();
        const employeeSelect = document.getElementById('employee');
        const employees = Array.from(employeeSelect.selectedOptions).map(option => option.value);
        const survey_start_date = document.getElementById('survey_start_date')?.value.trim();
        const survey_end_date = document.getElementById('survey_end_date')?.value.trim();

        let errors = [];

        if (!template) {
            errors.push("Survey is required.");
        }

        // if (!survey_name) {
        //     errors.push("Survey Name is required.");
        // }

        if (!employees.length) {
            errors.push("At least one employee must be selected.");
        }

        if (!survey_start_date) {
            errors.push("Survey start date is required.");
        } else if (isNaN(Date.parse(survey_start_date))) {
            errors.push("Survey start date must be a valid date.");
        }

        if (!survey_end_date) {
            errors.push("Survey end date is required.");
        } else if (isNaN(Date.parse(survey_end_date))) {
            errors.push("Survey end date must be a valid date.");
        }

        if (survey_start_date && survey_end_date && new Date(survey_start_date) > new Date(survey_end_date)) {
            errors.push("Survey end date must be after Survey start date.");
        }

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
            errorBox.innerHTML = '';
            form.submit(); // Native submit proceeds if all is valid
        }
    });
});


    $(function() {
        var surveyTemplateTable = $('.datatables-survey-assign'),
        select2 = $('.select2');
        if (surveyTemplateTable.length) {

            surveyTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('survey.user.assign') }}", // Fixed syntax
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
                        orderable: false,
                        searchable: false
                    },
                    { data: 'template_name', title: 'Survey Name' },
                    { data: 'survey_description', title: 'Description'},
                    { data: 'department', title: 'Department' },
                    { data: 'employees', title: 'Employees'},
                    { data: 'survey_start_date', title: 'Survey Start Date'},
                    { data: 'survey_end_date', title: 'Survey End Date'},
                    { data: 'created_by', title: 'Created By' },
                    {
                        targets: 7,
                        render: function(data, type, full, meta){
                            let survey_status = full['status'];
                            let survey_id = full['id'];
                            if(survey_status === 1){
                                displayType = `<button class="btn btn-sm btn-warning">Pending</button>`;
                            }
                            else if(survey_status === 2){
                                displayType = `<button class="btn btn-sm btn-success" onclick="showAnswerOffcanvas(${survey_id})">View Result</button>`;
                            }

                            return displayType;
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('survey_assigning.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-survey-assign" title="delete" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    /*delete thoughts function*/

    $(document).on('click', '.delete-survey-assign', function(e) {
        e.preventDefault();
        const surveyAssignId = $(this).data('id');

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
                        url: `/survey_assign/${surveyAssignId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "User Survey has been deleted.", "success").then(() => {
                                $('#datatables-survey-assign').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });



  function openSurveyAssignOffcanvas(surveyTemplateId) {

    if (surveyTemplateId) {
        $.ajax({
            url: `/surveytemplate/${surveyTemplateId}/fetch`,
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

                       if (q.answer_type === 'optional') {
                            const options = q.options || ['Option 1', 'Option 2', 'Option 3', 'Option 4'];

                            questionsHtml += `<div class="row">`; // Start row

                            options.forEach((opt, i) => {
                                questionsHtml += `
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question_${index}" id="q${index}_opt${i}" value="${opt}">
                                            <label class="form-check-label" for="q${index}_opt${i}">${opt}</label>
                                        </div>
                                    </div>
                                `;
                            });

                            questionsHtml += `</div>`; // End row
                        }
                        else if (q.answer_type === 'yes_no') {
                            ['Yes', 'No'].forEach((opt) => {
                                questionsHtml += `
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="question_${index}" id="q${index}_${opt}" value="${opt}">
                                        <label class="form-check-label" for="q${index}_${opt}">${opt}</label>
                                    </div>
                                `;
                            });
                        } else if (q.answer_type === 'description') {
                            questionsHtml += `
                                <textarea class="form-control" name="question_${index}" rows="3" placeholder="Enter your response here..."></textarea>
                            `;
                        } else {
                            questionsHtml += `<p class="text-muted">Unknown answer type</p>`;
                        }

                        questionsHtml += `</div>`;
                    });
                } else {
                    questionsHtml = '<p>No questions found.</p>';
                }

                $('#questionContainer').html(questionsHtml);

                // Show modal (not offcanvas)
                const modal = new bootstrap.Modal(document.getElementById('survey_question_view'));
                modal.show();
            }
        });
    }
}




function openSurveyTemplateOffcanvas(targetId = null) {
    $('#survey-assign-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Survey</h5><span class="text-white slogan">Edit Survey</span>`);
        $.ajax({
            url: `/surveytemplate/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                // let content = data.thoughts.thoughts_details;
                // let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

                // $('#target_id').val(data.thoughts.id);
                // $('#thoughts_title').val(data.thoughts.thoughts_title);
                // $('#display_date').val(data.thoughts.display_date);
                // $('#thoughts_details').val(cleanContent);
                // // document.getElementById('thoughts-editor').textContent = cleanContent;
                // quillEditor1.root.innerHTML = cleanContent;

                // const previewEdit = document.getElementById("PicturePreview");
                // previewEdit.src = `/storage/${data.thoughts.picture}`;;
                // previewEdit.style.display = "block";

                // $('#picture').val('');
            }
        });
    }
    var offcanvasElement = $('#survey_template_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}


function showAnswerOffcanvas(surveysId) {
    currentSurveyId = surveysId;

    if (surveysId) {
        $.ajax({
            url: `/usersurveys/${surveysId}/surveyanswerfetch`,
            type: 'GET',
            success: function (data) {
                // Set department info in subtitle
                $('.address-subtitle').text(
                    `Department: ${data.survey_info?.template?.department_info?.name ?? 'N/A'} | Created By: ${data.survey_info?.template?.creator?.name ?? 'N/A'}`
                );

                let questionsHtml = '';

                if (data.answers && data.answers.length > 0) {
                    data.answers.forEach((answer, index) => {
                        questionsHtml += `
                            <div class="mb-4">
                                <h6>Q${index + 1}: ${answer.question_text ?? 'No question text'}</h6>

                                <p><strong>Answer:</strong> ${answer.answer ?? '<span class="text-muted">No response</span>'}</p>
                            </div>
                            <hr>`;
                    });
                } else {
                    questionsHtml = '<p>No answers found.</p>';
                }

                $('#answerContainer').html(questionsHtml);

                const modal = new bootstrap.Modal(document.getElementById('survey_answer_view'));
                modal.show();
            },
            error: function () {
                $('#answerContainer').html('<p class="text-danger">Failed to load answers.</p>');
            }
        });
    }
}
</script>
@endpush
