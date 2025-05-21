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
                <h4 class="fw-bold py-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                <div class="row">
                    <div class="col-sm-12 d-flex justify-content-end mb-3">
                        <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                            <!-- {{ route('project.create') }} -->
                            <span>
                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block"> New</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-datatable table-responsive">
                        <div class=" float-end mt-15 mr-20">
                        </div>

                        <table class="datatables-basic datatables-recruitments table border-top table-stripedc table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Job Title</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Project Name</th>
                                    <th>Interviewer</th>
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

<!-- Add Project From -->
<div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="rrf_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i> 
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel">Create Recruitment </h5>
                <span class="text-white slogan">Create New Recruiments</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-recuitment-form action="#" />
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>

    $('.ql-toolbar').remove();
    const fullToolbar = [
            [{ font: [] }, { size: [] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ script: 'super' }, { script: 'sub' }],
            [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
            [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
            [{ direction: 'rtl' }],
            ['link', 'image', 'video', 'formula'],
            ['clean']
    ];

    var quillLoad, quill  = new Quill('#job-description', {
            theme: 'snow',
            placeholder: 'Type your reason here...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
        });

    $(function() {
        
        var recruitmentTable = $('.datatables-recruitments'),
            select2 = $('.select2');

        if (recruitmentTable.length) {            
            recruitmentTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('recruitments.draft-list') }}", // Fixed syntax
                    dataType: "json", 
                    dataSrc: "data"  
                },
                columns: [
                    { data: 'row', title: 'No.' },
                    { data: 'rrfDate', title: 'Date' },
                    { data: 'jobTitle', title: 'Job Title' },
                    { data: 'designation', title: 'Designation' },
                    { data: 'status', title: 'Status' },
                    { data: 'priority', title: 'Priority' },
                    { data: 'projectName', title: 'Project Name' },
                    { data: 'interviewer', title: 'Interviewer' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" onclick="openOffcanvas(${row.id})" class="btn btn-sm btn-icon btn-primary edit-project"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm  btn-icon btn-danger delete-project" onclick="deleteRecruitment(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i></button>
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
        
        $('#recuritment-form').find('button[type="submit"]').text('Save as RRF');

        $('#recuritment-form').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            // Capture Quill Content into hidden input before serializing
            $('#jobDescription').val(quill.root.innerHTML);
            const form = $(this);
            const formData = new FormData(this);
            const url = form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
                },
                success: function (response) {
                  //  alert('Saved successfully!');
                    toastr["success"](response.message);
                    form.trigger('reset');
                    form.find('.select2').val(null).trigger('change'); // Reset select2
                    quill.root.innerHTML = ''; // Clear Quill editor

                    let offcanvasElement = document.getElementById('rrf_offcanvas'); // Replace with actual ID
                    let offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);

                    // Hide it
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                    recruitmentTable.DataTable().ajax.reload(null, false);

                },
                error: function (xhr) {
                    let message = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        message = '';
                        $.each(xhr.responseJSON.errors, function (key, val) {
                            message += `${val}\n`;
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                },
                complete: function () {
                    form.find('button[type="submit"]').prop('disabled', false).text('Save as RRF');
                }
            });
        });

    });

    
    function deleteRecruitment(recruitmentId) {
        if (confirm('Are you sure you want to delete this recruitment?')) {
            $.ajax({
                url: "{{ route('recruitments.destroy', ':recruitment') }}".replace(':recruitment', recruitmentId), // ✅ Correct route name
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatables-recruitments').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }

    function openOffcanvas(targetId = null) {
        const $form = $('#recuritment-form');
        $form[0].reset(); // Reset the form
        $form.find('select').each(function () {
            $(this).val('').trigger('change'); // Reset value and update select2
        });

        $('#target_id').val(''); // Clear the hidden ID field
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Create Recruitment </h5><span class="text-white slogan">Create New Recruitment</span>`);
        
        const offcanvasElement = $('#rrf_offcanvas');
        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        
        if (targetId) {
            const url = "{{ route('recruitments.edit', ':recruitment') }}".replace(':recruitment', targetId);
            $('#target_id').val(targetId);

            $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Recruitment </h5><span class="text-white slogan">Edit New Recruitment</span>`);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    const fields = [
                        'rrfDate', 'jobTitle', 'designation', 'projectId', 'priority',
                        'interviewer', 'status', 'experience', 'skillRequired', 'remarks',
                        'branchId', 'positionId', 'empId', 'departmentId', 'shiftId',
                        'salaryRange', 'jobType', 'sittingArragement', 'minimumQualification',
                        'schoolingMedium', 'graduation', 'ageGroup', 'location',
                        'interviewPlace', 'referralIncentive', 'requireToAndFroCharge',
                        'seekApproval', 'noOfPersons', 'keyword' // added 'keyword' in case it's also multiple
                    ];

                    fields.forEach(field => {
                        const $el = $('#' + field);
                        if ($el.length) {
                            let value = data.recruitment?.[field] ?? null;

                            if ($el.prop('multiple')) {
                                // Handle comma-separated string or array
                                if (typeof value === 'string') {
                                    value = value.split(',');
                                } else if (!Array.isArray(value)) {
                                    value = [];
                                }
                            }

                            $el.val(value).trigger('change');
                        }
                    });

                    // Handle job description (rich text editor)
                    if ('jobDescription' in data.recruitment) {
                        const jobDesc = data.recruitment.jobDescription || '';

                        quillLoad.root.innerHTML =  jobDesc;
                        //quillLoad.clipboard.dangerouslyPasteHTML(jobDesc);
                        $('#jobDescription').val(jobDesc); // sync hidden input
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Failed to fetch recruitment data:', error);
                    alert('Failed to load recruitment data.');
                }
            });
        }

        
    }

    function saveAsDraft() {
        const form = $('#recuritment-form')[0];
        const formData = new FormData(form);

        // Append Quill content to hidden input (make sure Quill is already initialized globally)
        const quill = Quill.find(document.querySelector('#job-description')); // Get existing instance
        $('#jobDescription').val(quill.root.innerHTML);
        formData.set('job_description', quill.root.innerHTML); // optional: if API uses job_description directly

        // Append the draft status
        formData.append('draft_status', 1);

        const url = $(form).attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(form).find('button[type="submit"], button[onclick="saveAsDraft()"]').prop('disabled', true).text('Saving...');
            },
            success: function (response) {
                toastr["success"](response.message);
                $(form).trigger('reset');
                $(form).find('.select2').val(null).trigger('change');
                quill.root.innerHTML = '';

                const offcanvasElement = document.getElementById('rrf_offcanvas');
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (offcanvas) {
                    offcanvas.hide();
                    $('.datatables-recruitments').DataTable().ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                let message = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = '';
                    $.each(xhr.responseJSON.errors, function (key, val) {
                        message += `${val}\n`;
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            },
            complete: function () {
                $(form).find('button[type="submit"]').prop('disabled', false).text('Save');
                $(form).find('button[onclick="saveAsDraft()"]').prop('disabled', false).text('Save as Draft');
            }
        });
    }


    
    
</script>

@stop