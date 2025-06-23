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
                            <table class="hover_effect datatables-basic datatables-user-feedback table border-top table-stripedc" id="datatables-user-feedback">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Feedback Title</th>
                                        <th>Feedback Start Date</th>
                                        <th>Feedback End Date</th>
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
   <div class="modal fade" id="feedback_question_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-4">
            <div class="modal-header-custom">

                <h3 class="address-title">Feedback Questions</h3>
                <p class="address-subtitle" style="font-weight: bold; font-size: 13px;">Department</p>

               <div id="scoreSummary" class="mb-3" style="font-size: 15px;">
                    <strong>Rating Scored:</strong> <span id="ratingScored">0</span> |
                    <strong>Maximum Rating:</strong> <span id="maximumRating">0</span> |
                    <strong>Rating Percentage:</strong> <span id="ratingPercentage">0%</span> |
                    <strong>Rating Grade:</strong> <span id="ratingGrade">N/A</span>
                </div>

            </div>
            <form id="feedbackQuestionForm" action="{{ route('feedback_report.store') }}" method="post">
                @csrf
               <input type="hidden" name="total_score" id="totalScoreInput">
                <input type="hidden" name="maximum_score" id="maximumScoreInput">
                <input type="hidden" name="percentage" id="percentageInput">
                <input type="hidden" name="grade" id="gradeInput">
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
 <div class="modal fade" id="feedback_answer_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-add-new-address">
         <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">X</button>
        <div class="modal-content p-3 p-md-4">

                <div class="modal-header-custom">
                    <h3 class="address-title">Feedback Report</h3>
                    <p class="address-subtitle"></p>
                    <div id="employeeInfo" class="text-muted small " style="font-size: 13px; font-weight: bold; color:white!important;">
                        <!-- Employee details will be injected here -->
                        <strong>Employee Name:</strong> <span id="empName">N/A</span> |
                        <strong>Employee ID:</strong> <span id="empCode">N/A</span> |
                        <strong>Department:</strong> <span id="empDept">N/A</span> |
                        <strong>Designation:</strong> <span id="empDesig">N/A</span>
                    </div>
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
                 <div id="printButton">

                 </div>
            </div>
        </div>
    </div>
</div>
<!--view feedback question view model end -->

@stop
@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('feedbackQuestionForm');

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
        var feedbackTemplateTable = $('.datatables-user-feedback'),
        select2 = $('.select2');
        if (feedbackTemplateTable.length) {

            feedbackTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('user.feedbacks') }}", // Fixed syntax
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
                    { data: 'feedback_title', title: 'Feedback Title' },
                    { data: 'feedback_start_date', title: 'Feedback Start Date'},
                    { data: 'feedback_end_date', title: 'Feedback End Date'},
                    { data: 'created_by', title: 'Created By' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {

                            if (row.status === 1) {
                                 const editUrl = "{{ route('feedback_assigning.edit', ':id') }}".replace(':id', row.id);
                                 return `<a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary view-feedback-assign" title="Reply Feedback" onclick="openFeedbackQuestionOffcanvas(${row.id})"><i class="ti ti-keyboard"></i></a>`;
                            }else{
                                return `<a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary view-feedback-assign" title="Reply Feedback" onclick="showAnswerOffcanvas(${row.id})"><i class="ti ti-file"></i></a>`;
                            }

                        }
                    }
                ]
            });
        }
    });





function openFeedbackQuestionOffcanvas(feedbackId) {
    currentFeedbackId = feedbackId;

    if (feedbackId) {
        $.ajax({
            url: `/userfeedbacks/${feedbackId}/feedbackfetch`,
            type: 'GET',
            success: function (data) {

                $('#feedbackQuestionForm').prepend(`<input type="hidden" name="feedback_id" value="${feedbackId}">`);
                $('.address-subtitle').text('Department: ' + (data.department ?? 'N/A') + ' | Created By:' + (data.created_by ?? 'N/A'));
                let questionsHtml = '';
                if (data.questions && data.questions.length > 0) {
                    data.questions.forEach((q, index) => {


                      questionsHtml += `<div class="question-box" data-answer-type="${q.answer_type}" data-question-id="${q.question_id}">
                                        <input type="hidden" name="answers[${index}][question_id]" value="${q.question_id}">
                                        <p class="question-title">Q${index + 1}: ${q.question}</p>`;


                            const options = {'Outstanding': 5,'Very Good': 4,'Good': 3,'Average': 2,'Poor': 1};
                            questionsHtml += `<div class="row">`;
                                            Object.entries(options).forEach(([label, value], i) => {
                                                questionsHtml += `
                                                    <div class="col-md-2 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="answers[${index}][mark]" id="q${index}_opt${i}" value="${value}" required>
                                                            <label class="form-check-label" for="q${index}_opt${i}">${label}</label>
                                                        </div>
                                                    </div>`;
                                            });
                            questionsHtml += `</div>`;

                            questionsHtml += `
                                 <textarea class="form-control" name="answers[${index}][comment]" rows="3" placeholder="Enter your comments here..."></textarea>`;
                        questionsHtml += `</div>`; // End .question-box
                    });
                } else {
                    questionsHtml = '<p>No questions found.</p>';
                }

                $('#questionContainer').html(questionsHtml);
                const modal = new bootstrap.Modal(document.getElementById('feedback_question_view'));
                modal.show();
            }
        });
    }
}

$('#questionContainer').on('change', 'input[type=radio]', function () {
    updateScores();
});

function updateScores() {
    let total = 0;
    let count = 0;
    const maxPerQuestion = 5; // max value per question

    $('input[type=radio]:checked').each(function () {
        total += parseInt($(this).val());
        count++;
    });

    const maxScore = count * maxPerQuestion;
    const percentage = maxScore > 0 ? ((total / maxScore) * 100).toFixed(2) : 0;

    let grade = 'N/A';
    if (percentage >= 90) grade = 'Outstanding';
    else if (percentage >= 80) grade = 'Very Good';
    else if (percentage >= 60) grade = 'Good';
    else if (percentage >= 40) grade = 'Average';
    else if (percentage >= 20) grade = 'Poor';

    // Update UI
    $('#ratingScored').text(total);
    $('#maximumRating').text(maxScore);
    $('#ratingPercentage').text(`${percentage}%`);
    $('#ratingGrade').text(grade);

    // Update hidden inputs
    $('#totalScoreInput').val(total);
    $('#maximumScoreInput').val(maxScore);
    $('#percentageInput').val(percentage);
    $('#gradeInput').val(grade);
}


function showAnswerOffcanvas(feedbackId) {
    currentFeedbackId = feedbackId;

    if (feedbackId) {
        $.ajax({
            url: `/userfeedbacks/${feedbackId}/feedbackanswerfetch`,
            type: 'GET',
            success: function (data) {
                // Header subtitle
                $('.address-subtitle').text(
                    `Created By: ${data.feedback_info?.feedback?.creator?.full_name ?? 'N/A'}`
                );

                // $('.address-subtitle').text(
                //     `Department: ${data.sar_info?.template?.department_info?.name ?? 'N/A'} | Created By: ${data.sar_info?.template?.creator?.name ?? 'N/A'}`
                // );


                let questionsHtml = '';
                let printButton = '';
                // Employee details
                const emp = data.employee_details;
                const dates = data.feedback_dates;
                $('#empName').text(emp.full_name ?? 'N/A');
                $('#empCode').text(emp.employee_code ?? 'N/A');
                $('#empDept').text(emp.department ?? 'N/A');
                $('#empDesig').text(emp.designation ?? 'N/A');

                // Score Summary
                questionsHtml += `
                    <div class="mb-4 p-3 bg-light border rounded">
                        <h5>Score Summary</h5>
                        <div class="d-flex flex-wrap gap-4">
                            <span><strong>Total Score:</strong> ${data.total_score}</span>
                            <span><strong>Maximum Score:</strong> ${data.maximum_score}</span>
                            <span><strong>Score Percentage:</strong> ${data.score_percent}%</span>
                             <span><strong>Grade:</strong> ${data.grade}</span>
                        </div>
                    </div>`;

                // Answers
                if (data.answers && data.answers.length > 0) {
                    data.answers.forEach((answer, index) => {
                    let gradeText = '';
                    if (answer.mark !== null) {
                        switch (answer.mark) {
                            case 5: gradeText = 'Outstanding'; break;
                            case 4: gradeText = 'Very Good'; break;
                            case 3: gradeText = 'Good'; break;
                            case 2: gradeText = 'Average'; break;
                            case 1: gradeText = 'Poor'; break;
                        }
                    }

                    questionsHtml += `
                        <div class="mb-4">
                            <h6>Q${index + 1}: ${answer.question_text ?? 'No question text'}</h6>
                            ${answer.mark !== null ? `<p><strong>Grade:</strong> ${gradeText}</p>` : ''}
                            <p><strong>Comment:</strong> ${answer.answer ?? '<span class="text-muted">No response</span>'}</p>
                        </div>
                        <hr>`;
                });
                } else {
                    questionsHtml += '<p>No answers found.</p>';
                }

                $('#answerContainer').html(questionsHtml);

                 printButton += `<button type="button" class="btn btn-primary" onclick="printFeedbackReport(`+feedbackId+`)">
                                    Download
                                </button>`;
                $('#printButton').html(printButton);



                const modal = new bootstrap.Modal(document.getElementById('feedback_answer_view'));
                modal.show();
            },
            error: function () {
                $('#answerContainer').html('<p class="text-danger">Failed to load answers.</p>');
            }
        });
    }
}


// print sar
function printFeedbackReport(feedbackId) {

    $.ajax({
        url: `/userfeedbacks/${feedbackId}/generate-pdf`,
        type: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            const blob = new Blob([response], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = `Feedback_Report_${feedbackId}.pdf`;
            link.click();
        },
        error: function () {
            alert('Failed to generate PDF.');
        }
    });
}


</script>
@endpush
