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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openJobOffcanvas()">
                                <!-- {{ route('project.create') }} -->
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New Jobs</span>
                                </span>
                            </a>
                        </div>

                        @if(isset($jobs) && count($jobs) > 0)
                            @foreach($jobs as $job)
                                <div class="col-sm-4 mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between bg-light" style="padding: 1rem 1rem !important;">
                                            <div class="card-title mb-0">
                                                <h5 class="m-0">{{ $job->title }}</h5>
                                                <small class="text-muted">{{ $job->createdBy?->full_name }} | {{ date('d M Y H:i', strtotime($job->created_at)) }} </small>
                                            </div>
                                            <span class="d-flex align-items-center gap-1">
                                                <a class="text-primary" href="javascript:void(0);" onclick="viewJobOffcanvas({{ $job->id }})"><i class="ti ti-eye"></i></a>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="earningReports" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReports" style="">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="openJobOffcanvas({{ $job->id }})">Edit</a>
                                                    <a class="dropdown-item" href="{{ route('jobs.destroy', $job->id) }}" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="card-body p-3">
                                            @if($job->assigned_to)
                                                <div class="mb-2 text-muted" style="font-size: 12px !important;">
                                                    Assigned To: <span class="badge bg-label-primary">{{ $job->assignedTo?->full_name }}</span>
                                                </div>
                                            @endif

                                            @php
                                                $fullDescription = strip_tags($job->job_description); // Get clean text
                                                $limit = 100; // Set your desired character limit for the preview

                                                if (strlen($fullDescription) > $limit) {
                                                    $limitedDescription = Str::limit($fullDescription, $limit); // Truncate using Laravel's Str::limit
                                                    $showReadMore = true;
                                                } else {
                                                    $limitedDescription = $fullDescription;
                                                    $showReadMore = false;
                                                }
                                            @endphp

                                            {{ $limitedDescription }}

                                            @if ($showReadMore)
                                                | <a href="javascript:void(0);" onclick="viewJobOffcanvas({{ $job->id }})" class="read-more-link text-primary" style="font-size: 12px !important;">Read More</a>
                                            @endif
                                        </div>
                                        <div class="card-footer d-flex justify-content-between text-muted p-3">
                                            <span style="font-size: 11px !important;">Last updated: {{ \App\Helpers\CustomHelper::getTimeAge($job->updated_at) }} </span>
                                            <span style="font-size: 11px !important;" ><a href="#"><i class="ti ti-message me-1"></i>{{ $job->comments_count }}</a></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="job_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Job</h5>
                <span class="text-white slogan">Create New Job</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body" style="overflow: visible!important;" >
        <div class="row">
            <div class="col-sm-12">
                <x-user-job-form />
            </div>
        </div>
    </div>
</div>

<!-- Views Task -->
<div class="offcanvas offcanvas-end w-50" data-bs-backdrop="static" tabindex="-1" id="view_job_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> View Jobs</h5>
                <span class="text-white slogan">View Job</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body" id="view_job_offcanvas_body" >
        <div class="row">
            <div class="col-sm-12">
                No Data Available ..
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    // form validation
    var quillEditor, quillEditor1 = new Quill('#job_description_editor', { theme: 'snow',
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
        const form = document.getElementById('job_form');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values
            const job_title          = document.getElementById('title').value.trim();
            const job_editor         = quillEditor1.root.innerText.trim();
            const hidden_job_details = document.getElementById('job_description');

            hidden_job_details.value = job_editor;

            let errors = [];

            // === Validation ===
            if (!job_title) {
                errors.push("Job Title is required.");
            }

            if (!hidden_job_details) {
                errors.push("Job details reason is required");
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
        var select2 = $('.select2');

    });

function openJobOffcanvas(targetId = null) {
    $('#job_form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        let url = "{{ route('job.edit', ':job') }}".replace(':job', targetId);

        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Jobs</h5><span class="text-white slogan">Edit New Jobs</span>`);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {

                let content = data.job.job_description;
                let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

                $('#target_id').val(data.job.id);
                $('#title').val(data.job.title);
                $('#job_description').val(cleanContent);
                $('#assigned_to').val(data.job.title).trigger('change');
                // document.getElementById('thoughts-editor').textContent = cleanContent;
                quillEditor1.root.innerHTML = cleanContent;
            }
        });
    }
    var offcanvasElement = $('#job_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

function viewJobOffcanvas(targetId = null) {
    let url = "{{ route('job.show', ':job') }}".replace(':job', targetId);
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            var offcanvasElement = $('#view_job_offcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            $('#view_job_offcanvas_body').html(response.html);
            offcanvas.show();
        }
    });
}

function addComment() {
    let form = $('#job_comments_form');
    let submitBtn = form.find('#comment_btn');
    let commentBox = $('#job_comment');

    // Disable button to prevent multiple clicks
    submitBtn.prop('disabled', true).text('Submitting...');

    $.ajax({
        url: "{{ route('job.comment.store') }}",
        method: "POST",
        data: form.serialize(),
        success: function (response) {
            if (response.status) {
                $('#job_comments_list').prepend(response.comment);
                commentBox.val(''); // Clear the textarea
            } else {
                alert('Something went wrong!');
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errors = xhr.responseJSON.errors;
                alert(Object.values(errors).join('\n'));
            } else {
                alert('An unexpected error occurred.');
            }
        },
        complete: function () {
            // Re-enable button
            submitBtn.prop('disabled', false).text('Submit');
        }
    });
}
</script>
@endpush
