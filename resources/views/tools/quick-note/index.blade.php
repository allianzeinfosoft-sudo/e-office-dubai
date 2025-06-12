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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openQuickNoteOffcanvas()">
                                <!-- {{ route('project.create') }} -->
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>

                        @if(isset($quick_notes) && count($quick_notes) > 0)
                            @foreach($quick_notes as $quick_note)
                                <div class="col-sm-4 mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between bg-light" style="padding: 1rem 1rem !important;">
                                            <div class="card-title mb-0">
                                                <h5 class="m-0">{{ $quick_note->title }}</h5>
                                                <small class="text-muted">{{ $quick_note->createdBy?->full_name }} | {{ date('d M Y H:i', strtotime($quick_note->created_at)) }} </small>
                                            </div>
                                            <span class="d-flex align-items-center gap-1">
                                                <a class="text-primary" href="javascript:void(0);" onclick="viewQuickNoteOffcanvas({{ $quick_note->id }})"><i class="ti ti-eye"></i></a>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="earningReports" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReports" style="">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="openQuickNoteOffcanvas({{ $quick_note->id }})">Edit</a>
                                                    <a class="dropdown-item" href="{{ route('tools.quick-note.destroy', $quick_note->id) }}" onclick="return confirm('Are you sure you want to delete this quick note?')">Delete</a>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="card-body p-3">
                                            @if($quick_note->assigned_to)
                                                <div class="mb-2 text-muted" style="font-size: 12px !important;">
                                                    Assigned To: <span class="badge bg-label-primary">{{ $quick_note->assignedTo?->full_name }}</span>
                                                </div>
                                            @endif

                                            @php
                                                $fullDescription = strip_tags($quick_note->note_description); // Get clean text
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
                                                | <a href="javascript:void(0);" onclick="viewQuickNoteOffcanvas({{ $quick_note->id }})" class="read-more-link text-primary" style="font-size: 12px !important;">Read More</a>
                                            @endif
                                        </div>
                                        <div class="card-footer d-flex justify-content-between text-muted p-3">
                                            <span style="font-size: 11px !important;">Last updated: {{ \App\Helpers\CustomHelper::getTimeAge($quick_note->updated_at) }} </span> 
                                            <span style="font-size: 11px !important;" ><a href="#"><i class="ti ti-message me-1"></i>{{ $quick_note->comments_count }}</a></span>
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="quick_note_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Quick Note</h5>
                <span class="text-white slogan">Create New Quick Note</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body" style="overflow: visible!important;" >
        <div class="row">
            <div class="col-sm-12">
                <x-quick-note-from />
            </div>
        </div>
    </div>
</div>

<!-- Views Task -->
<div class="offcanvas offcanvas-end w-50" data-bs-backdrop="static" tabindex="-1" id="view_quick_note_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> View Quick Note</h5>
                <span class="text-white slogan">View Quick Note</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body" id="view_quick_note_offcanvas_body" >
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
    var quillEditor, quillEditor1 = new Quill('#note_description_editor', { theme: 'snow',
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
        const form = document.getElementById('quick_note_form');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values
            const quick_note_title          = document.getElementById('title').value.trim();
            const quick_note_editor         = quillEditor1.root.innerText.trim();
            const hidden_quick_note_details = document.getElementById('note_description');

            hidden_quick_note_details.value = quick_note_editor;

            let errors = [];

            // === Validation ===
            if (!quick_note_title) {
                errors.push("Quick Note Title is required.");
            }

            if (!hidden_quick_note_details) {
                errors.push("Quick Note details reason is required");
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

function openQuickNoteOffcanvas(targetId = null) {
    $('#quick_note_form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        let url = "{{ route('tools.quick-note.edit', ':quickNote') }}".replace(':quickNote', targetId);

        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Thoughts</h5><span class="text-white slogan">Edit New Thoughts</span>`);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {

                let content = data.quick_note.note_description;
                let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

                $('#target_id').val(data.quick_note.id);
                $('#title').val(data.quick_note.title);
                $('#note_description').val(cleanContent);
                $('#assigned_to').val(data.quick_note.title).trigger('change');
                // document.getElementById('thoughts-editor').textContent = cleanContent;
                quillEditor1.root.innerHTML = cleanContent;
            }
        });
    }
    var offcanvasElement = $('#quick_note_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

function viewQuickNoteOffcanvas(targetId = null) {
    let url = "{{ route('tools.quick-note.show', ':quickNote') }}".replace(':quickNote', targetId);
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            var offcanvasElement = $('#view_quick_note_offcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            $('#view_quick_note_offcanvas_body').html(response.html);
            offcanvas.show();
        }
    });
}

function addComment() {
    let form = $('#quick_note_comments_form');
    let submitBtn = form.find('#comment_btn');
    let commentBox = $('#note_comment');

    // Disable button to prevent multiple clicks
    submitBtn.prop('disabled', true).text('Submitting...');

    $.ajax({
        url: "{{ route('quick-note.comment.store') }}",
        method: "POST",
        data: form.serialize(),
        success: function (response) {
            if (response.status) {
                $('#quick_note_comments_list').prepend(response.comment);
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
