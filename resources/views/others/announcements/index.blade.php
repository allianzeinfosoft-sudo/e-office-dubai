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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Others /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New</span>
                                </span>
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-annoncement">
                                <div class=" float-end mt-15 mr-20"></div>
                                <table class="datatables-basic datatables-announcement table border-top table-stripedc table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Title</th>
                                            <th>Details</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>

                <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Project From -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="announcement_offcanvas" aria-labelledby="staticBackdropLabel">
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
                <x-announcement-form />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
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

    var quillAnnouncement  = new Quill('#description-editor', {
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


    $(function(){

        const endPicker = $("#display_end_date").flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        $("#display_start_date").flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y',
            onChange: function(selectedDates, dateStr, instance) {
                // Set minDate of end date based on start date
                $("#display_end_date")[0]._flatpickr.set('minDate', dateStr);
            }
        });

        var annoncementTable = $('.datatables-announcement');

        if(annoncementTable.length){

            annoncementTable.DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    type: "GET",
                    url: "{{ route('others.announcements.index') }}", // Fixed syntax
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'row', name: 'No' },
                    { data: 'name_announcement', name: 'Title' },
                    { data: 'description', name: 'Details' },
                    { data: 'display_start_date', name: 'From' },
                    { data: 'display_end_date', name: 'To' },
                    { data: 'createdAt', name: 'Created' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" onclick="openOffcanvas(${row.id})" class="btn btn-sm btn-icon btn-primary edit-project"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm  btn-icon btn-danger delete-project" onclick="deleteAnnouncement(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        }


        $('#announcement-form').on('submit', function (e) {
            e.preventDefault();
            $('#description').val(quillAnnouncement.root.innerHTML);
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
                    quillAnnouncement.root.innerHTML = '';
                    let offcanvasElement = document.getElementById('announcement_offcanvas');
                    let offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    // Hide it
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                    annoncementTable.DataTable().ajax.reload(null, false);
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
                    //ert(message);
                    toastr["error"](message);
                },
                complete: function () {
                    form.find('button[type="submit"]').prop('disabled', false).text('Save');
                }
            });
        });

    });

    function openOffcanvas(targetId = null) {
        const $form = $('#announcement-form');
        $form[0].reset(); // Reset the form
        $('#target_id').val(''); // Clear the hidden ID field
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Create Announcement </h5><span class="text-white slogan">Create New Announcement</span>`);
        const offcanvasElement = $('#announcement_offcanvas');
        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }


        if (targetId) {
            const url = "{{ route('others.announcements.edit', ':announcement') }}".replace(':announcement', targetId);
            $('#target_id').val(targetId);

            $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Announcement </h5><span class="text-white slogan">Edit Announcement</span>`);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#name_announcement').val(data.announcement.name_announcement);
                    $('#display_start_date').flatpickr().setDate(data.announcement.display_start_date, true);
                    $('#display_end_date').flatpickr().setDate(data.announcement.display_end_date, true);

                    const anoDesc = data.announcement.description || '';

                    quillAnnouncement.root.innerHTML =  anoDesc;
                    //quillLoad.clipboard.dangerouslyPasteHTML(jobDesc);
                    $('#description').val(jobDesc); // sync hidden input

                },
                error: function (xhr, status, error) {
                    console.error('Failed to fetch recruitment data:', error);
                    alert('Failed to load recruitment data.');
                }
            });
        }
    }

    function deleteAnnouncement(announcementId) {
        if (confirm('Are you sure you want to delete this announcement?')) {
            $.ajax({
                url: "{{ route('others.announcements.destroy', ':announcement') }}".replace(':announcement', announcementId), // ✅ Correct route name
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatables-announcement').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }
</script>
@endpush
