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
                                    <span class="d-none d-sm-inline-block">Assign Template</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-sar-assign table border-top table-stripedc" id="datatables-sar-assign">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                         <th>Employee</th>
                                        <th>Template Name</th>
                                        <th>SAR Name</th>
                                        <th>Department</th>
                                        <th>SAR Start Date</th>
                                        <th>SAR End Date</th>
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

<div class="offcanvas offcanvas-end w-25" data-bs-backdrop="static" tabindex="-1" id="sar_template_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Assign SAR</h5>
                <span class="text-white slogan">Assign SAR to Employees</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-s-a-r-assign-form/>
            </div>
        </div>
    </div>
</div>


<!-- question view mode -->
   <div class="modal fade" id="sar_question_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
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

            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                Close
            </button>
        </div>
    </div>
</div>
<!-- end question view model-->


<!-- view sar answer sheet -->
 <div class="modal fade" id="sar_answer_view" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-add-new-address">
         <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">X</button>
        <div class="modal-content p-3 p-md-4">

                <div class="modal-header-custom">
                    <h3 class="address-title">Self Appraisal Report</h3>
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
<!--view sar question view model end -->


@stop


@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('sar-assign-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const department = document.getElementById('department')?.value.trim();
        const template = document.getElementById('template')?.value.trim();
        const sar_name =  document.getElementById('sar_name')?.value.trim();
        const employeeSelect = document.getElementById('employee');
        const employees = Array.from(employeeSelect.selectedOptions).map(option => option.value);
        const sar_start_date = document.getElementById('sar_start_date')?.value.trim();
        const sar_end_date = document.getElementById('sar_end_date')?.value.trim();

        let errors = [];

        if (!template) {
            errors.push("Template is required.");
        }

        if (!sar_name) {
            errors.push("SAR Name is required.");
        }

        if (!employees.length) {
            errors.push("At least one employee must be selected.");
        }

        if (!sar_start_date) {
            errors.push("SAR start date is required.");
        } else if (isNaN(Date.parse(sar_start_date))) {
            errors.push("SAR start date must be a valid date.");
        }

        if (!sar_end_date) {
            errors.push("SAR end date is required.");
        } else if (isNaN(Date.parse(sar_end_date))) {
            errors.push("SAR end date must be a valid date.");
        }

        if (sar_start_date && sar_end_date && new Date(sar_start_date) > new Date(sar_end_date)) {
            errors.push("SAR end date must be after SAR start date.");
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
        var sarTemplateTable = $('.datatables-sar-assign'),
        select2 = $('.select2');
        if (sarTemplateTable.length) {

            sarTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('sar.user.assign') }}", // Fixed syntax
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
                    { data: 'employees', title: 'Employee'},
                    { data: 'template_name', title: 'Template Name' },
                    { data: 'sar_name', title: 'SAR Name'},
                    { data: 'department', title: 'Department' },
                    { data: 'sar_start_date', title: 'SAR Start Date'},
                    { data: 'sar_end_date', title: 'SAR End Date'},
                    { data: 'created_by', title: 'Created By' },
                    {
                        targets: 7,
                        render: function(data, type, full, meta){
                            let sar_status = full['status'];
                            let sar_id = full['id'];
                            if(sar_status === 1){
                                displayType = `<button class="btn btn-sm btn-warning">Pending</button>`;
                            }
                            else if(sar_status === 2){
                                displayType = `<button class="btn btn-sm btn-success" onclick="showAnswerOffcanvas(${sar_id})">View Result</button>`;
                            }

                            return displayType;
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('sar_assigning.edit', ':id') }}".replace(':id', row.id);

                            if( row.status == 1)
                            {
                                return `<a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-sar-assign" title="edit" onclick="openSarAssignOffcanvas(${row.id})"><i class="ti ti-eye"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-sar-assign" title="delete" data-id="${row.id}"><i class="ti ti-trash"></i></a>`;
                            }
                            else if(row.status  == 2)
                            {
                                return `<a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-sar-assign" title="delete" data-id="${row.id}"><i class="ti ti-trash"></i></a>`;
                            }
                            else
                            {
                                return null;
                            }

                        }
                    }
                ]
            });
        }
    });

    /*delete thoughts function*/

    $(document).on('click', '.delete-sar-assign', function(e) {
        e.preventDefault();
        const sarAssignId = $(this).data('id');

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
                        url: `/sar_assign/${sarAssignId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "User SAR has been deleted.", "success").then(() => {
                                $('#datatables-sar-assign').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });



  function openSarAssignOffcanvas(sarTemplateId) {

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
                const modal = new bootstrap.Modal(document.getElementById('sar_question_view'));
                modal.show();
            }
        });
    }
}




function openSarTemplateOffcanvas(targetId = null) {
    $('#sar-assign-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Sar Template</h5><span class="text-white slogan">Edit Sar Template</span>`);
        $.ajax({
            url: `/sartemplate/${targetId}/edit`,
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
    var offcanvasElement = $('#sar_template_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}


function showAnswerOffcanvas(sarsId) {
    currentSarId = sarsId;

    if (sarsId) {
        $.ajax({
            url: `/usersars/${sarsId}/saranswerfetch`,
            type: 'GET',
            success: function (data) {
                // Header subtitle
                $('.address-subtitle').text(
                    `Created By: ${data.sar_info?.template?.creator?.full_name ?? 'N/A'}`
                );

                // $('.address-subtitle').text(
                //     `Department: ${data.sar_info?.template?.department_info?.name ?? 'N/A'} | Created By: ${data.sar_info?.template?.creator?.name ?? 'N/A'}`
                // );


                let questionsHtml = '';
                let printButton = '';
                // Employee details
                const emp = data.employee_details;
                const dates = data.sar_dates;
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
                    </div>
                `;

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

                 printButton += `<button type="button" class="btn btn-primary" onclick="printSarReport(`+sarsId+`)">
                                    Download
                                </button>`;
                $('#printButton').html(printButton);



                const modal = new bootstrap.Modal(document.getElementById('sar_answer_view'));
                modal.show();
            },
            error: function () {
                $('#answerContainer').html('<p class="text-danger">Failed to load answers.</p>');
            }
        });
    }
}


// print sar
function printSarReport(sarsId) {
    $.ajax({
        url: `/usersars/${sarsId}/generate-pdf`,
        type: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (response) {
            const blob = new Blob([response], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = `Self_Appraisal_Report_${sarsId}.pdf`;
            link.click();
        },
        error: function () {
            alert('Failed to generate PDF.');
        }
    });
}
</script>
@endpush
