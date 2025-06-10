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

                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between bg-light" style="padding: 1rem 1rem !important;">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0">Title</h5>
                                        <small class="text-muted">Created by / date</small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="earningReports" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReports" style="">
                                        <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-3 mt-2">
                                    <p>Summanry text</p>
                                </div>
                                 <div class="card-footer d-flex justify-content-between text-muted p-3">
                                    <span stye="font-size: 9px !important;">1 minuts ago </span> 
                                     <span stye="font-size: 9px !important;" ><a href="#"><i class="ti ti-message me-1"></i>121</a></span>
                                 </div>
                            </div>
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
            const quick_note_title          = document.getElementById('thoughts_title').value.trim();
            const quick_note_editor         = quillEditor1.root.innerText.trim();
            const hidden_quick_note_details = document.getElementById('note_description');

            hidden_quick_note_details.value = thoughts_details;

            let errors = [];

            // === Validation ===
            if (!thoughts_title) {
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

    /*delete thoughts function*/

    $(document).on('click', '.delete-thoughts', function(e) {
        e.preventDefault();
        const thoughtId = $(this).data('id');

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
                        url: `/thoughts/${thoughtId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Thought has been deleted.", "success").then(() => {
                                $('#datatables-thoughts').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openQuickNoteOffcanvas(targetId = null) {
    $('#quick_note_form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Thoughts</h5><span class="text-white slogan">Edit New Thoughts</span>`);
        $.ajax({
            url: `/thoughts/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                let content = data.thoughts.thoughts_details;
                let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

                $('#target_id').val(data.thoughts.id);
                $('#thoughts_title').val(data.thoughts.thoughts_title);
                $('#display_date').val(data.thoughts.display_date);
                $('#thoughts_details').val(cleanContent);
                // document.getElementById('thoughts-editor').textContent = cleanContent;
                quillEditor1.root.innerHTML = cleanContent;

                const previewEdit = document.getElementById("PicturePreview");
                previewEdit.src = `/storage/${data.thoughts.picture}`;;
                previewEdit.style.display = "block";

                $('#picture').val('');
            }
        });
    }
    var offcanvasElement = $('#quick_note_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

</script>
@endpush
