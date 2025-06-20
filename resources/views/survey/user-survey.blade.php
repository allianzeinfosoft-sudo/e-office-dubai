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

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-user-surveys table border-top table-stripedc" id="datatables-user-surveys">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Survey Name</th>
                                        <th>Survey Start Date</th>
                                        <th>Survey End Date</th>
                                        <th>Created By</th>
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




<!-- question view mode -->
   <div class="modal fade" id="survey_question_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-4">
            <div class="modal-header-custom">

                <h3 class="address-title">Survey Question</h3>
                <p class="  address-subtitle">Department</p>
            </div>
            <form id="surveyQuestionForm" action="{{ route('survey.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div id="questionContainer" class="col-12">
                        <!-- Questions will be injected here -->
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </form>
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
                <p class="address-subtitle"></p>
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
                    <div id="printButton"> </div>
                </div>
        </div>
    </div>
</div>
<!--view sar question view model end -->

@stop


@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('surveyQuestionForm');

        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            let errors = [];

            // Check if at least one question is answered
            const inputs = form.querySelectorAll('input[type="radio"]:checked, textarea');
            if (inputs.length === 0) {
                errors.push("Please answer at least one question before submitting.");
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
                form.submit(); // Proceed with form submission
            }
        });
    });



    $(function() {
        var surveyTemplateTable = $('.datatables-user-surveys'),
        select2 = $('.select2');
        if (surveyTemplateTable.length) {

            surveyTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('user.surveys') }}", // Fixed syntax
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
                    { data: 'survey_start_date', title: 'Survey Start Date'},
                    { data: 'survey_end_date', title: 'Survey End Date'},
                    { data: 'created_by', title: 'Created By' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {

                            if (row.status === 1) {
                                 const editUrl = "{{ route('survey_assigning.edit', ':id') }}".replace(':id', row.id);
                                 return `<a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary view-survey-assign" title="Reply Survey" onclick="openSurveyQuestionOffcanvas(${row.id})"><i class="ti ti-keyboard"></i></a>`;
                            }else{
                                return `<a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary view-survey-assign" title="Reply Survey" onclick="showAnswerOffcanvas(${row.id})"><i class="ti ti-file"></i></a>`;
                            }

                        }
                    }
                ]
            });
        }
    });





function openSurveyQuestionOffcanvas(surveysId) {
    currentSurveyId = surveysId;

    if (surveysId) {
        $.ajax({
            url: `/usersurveys/${surveysId}/surveyfetch`,
            type: 'GET',
            success: function (data) {

                $('#surveyQuestionForm').prepend(`<input type="hidden" name="survey_id" value="${surveysId}">`);
                $('.address-subtitle').text('Department: ' + (data.department ?? 'N/A') + ' | Created By: ' + (data.created_by ?? 'N/A') );

                let questionsHtml = '';

                 questionsHtml += `
                    <div class="mb-4 p-3 bg-light border rounded">
                        <h5>Score Summary</h5>
                        <div class="d-flex flex-wrap gap-4">
                            <span><strong>Total Score:</strong> ${data.department}</span>
                            <span><strong>Maximum Score:</strong> ${data.department}</span>
                            <span><strong>Score Percentage:</strong> ${data.department}%</span>
                             <span><strong>Grade:</strong> ${data.department}</span>
                        </div>
                    </div>`;


                if (data.questions && data.questions.length > 0) {
                    data.questions.forEach((q, index) => {


                      questionsHtml += `<div class="question-box" data-answer-type="${q.answer_type}" data-question-id="${q.question_id}">
                                        <input type="hidden" name="answers[${index}][question_id]" value="${q.question_id}">
                                        <input type="hidden" name="answers[${index}][answer_type]" value="${q.answer_type}">
                                        <p class="question-title">Q${index + 1}: ${q.question}</p>`;

                        if (q.answer_type === 'optional') {
                            const options = q.options || ['Option 1', 'Option 2', 'Option 3', 'Option 4'];
                            questionsHtml += `<div class="row">`;
                            options.forEach((opt, i) => {
                                questionsHtml += `
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[${index}][answer]" id="q${index}_opt${i}" value="${opt}">
                                            <label class="form-check-label" for="q${index}_opt${i}">${opt}</label>
                                        </div>
                                    </div>`;
                            });
                            questionsHtml += `</div>`;
                        }
                        else if (q.answer_type === 'yes_no') {
                            ['Yes', 'No'].forEach((opt) => {
                                questionsHtml += `
                                     <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="answers[${index}][answer]" id="q${index}_${opt}" value="${opt}">
                                        <label class="form-check-label" for="q${index}_${opt}">${opt}</label>
                                    </div>`;
                            });
                        } else if (q.answer_type === 'description') {
                            questionsHtml += `
                                 <textarea class="form-control" name="answers[${index}][answer]" rows="3" placeholder="Enter your response here..."></textarea>`;
                        } else {
                            questionsHtml += `<p class="text-muted">Unknown answer type</p>`;
                        }

                        questionsHtml += `</div>`; // End .question-box
                    });
                } else {
                    questionsHtml = '<p>No questions found.</p>';
                }

                $('#questionContainer').html(questionsHtml);

                const modal = new bootstrap.Modal(document.getElementById('survey_question_view'));
                modal.show();
            }
        });
    }
}



function showAnswerOffcanvas(surveysId) {
    currentarId = surveysId;

    if (surveysId) {
        $.ajax({
            url: `/usersurveys/${surveysId}/surveyanswerfetch`,
            type: 'GET',
            success: function (data) {
                // Set department info in subtitle
                $('.address-subtitle').text(
                    `Survey Name: ${data.survey_info?.template?.template_name ?? 'N/A'}`
                );

                let questionsHtml = '';
                let printButton = '';
                questionsHtml += `
                    <div class="mb-4 p-3 bg-light border rounded">
                        <h5>Survey Info</h5>
                        <div class="d-flex flex-wrap gap-4">
                            <span><strong>Employee Name:</strong> ${data.survey_info?.employee?.full_name}</span>
                            <span><strong>Department:</strong> ${data.survey_info?.template?.department_info?.department ?? 'N/A'}</span>

                        </div>
                    </div>`;
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

                printButton += `<button type="button" class="btn btn-primary" onclick="printSuveyReport(`+surveysId+`)">
                                    Download
                                </button>`;
                $('#printButton').html(printButton);

                const modal = new bootstrap.Modal(document.getElementById('survey_answer_view'));
                modal.show();
            },
            error: function () {
                $('#answerContainer').html('<p class="text-danger">Failed to load answers.</p>');
            }
        });
    }
}

// print survey
function printSuveyReport(surveyId) {
    $.ajax({
        url: `/usersurveys/${surveyId}/generate-pdf`,
        type: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            const blob = new Blob([response], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = `Self_Appraisal_Report_${surveyId}.pdf`;
            link.click();
        },
        error: function () {
            alert('Failed to generate PDF.');
        }
    });
}
</script>
@endpush
