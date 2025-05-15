@extends('layouts.app')

@section('css')
<style>
 
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
                <h4 class="fw-bold py-3"><span class="text-muted fw-light">Recruitments /</span> {{ $meta_title }}</h4>

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

@stop

@section('js')
<script>

    var recruitmentTable = $('.datatables-recruitments'),
    select2 = $('.select2');

    $(function() {

        const applicationStatus = @json(config('optionsData.applicationStatus'));
        
        if (recruitmentTable.length) {            
            recruitmentTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('recruitments.rrf-approvals') }}", // Fixed syntax
                    dataType: "json", 
                    dataSrc: "data"  
                },
                columns: [
                    { data: 'row', title: 'No.' },
                    { data: 'rrfDate', title: 'Date' },
                    { data: 'jobTitle', title: 'Job Title' },
                    { data: 'designation', title: 'Designation' },
                    { data: 'status', title: 'Status', 
                        render: function (data, type, row) {
                            const colors = {
                                0: 'warning',
                                1: 'danger',
                                2: 'primary',
                                3: 'info',
                                4: 'success'
                            };
                                        
                            return `
                                <span class="badge bg-${colors[row.status] ?? 'warning'} bg-glow"> ${applicationStatus[row.status] ?? 'Pending'} </span>
                            `;
                        }
                    },
                    { data: 'priority', title: 'Priority' },
                    { data: 'projectName', title: 'Project Name' },
                    { data: 'interviewer', title: 'Interviewer' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-info">Approve</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary edit-project">Reject</a>
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
                    form.find('button[type="submit"]').prop('disabled', false).text('Save');
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
                $(form).find('button[type="submit"]').prop('disabled', false).text('Save');
                $(form).find('button[onclick="saveAsDraft()"]').prop('disabled', false).text('Save as Draft');
            }
        });
    }

    
</script>

@stop