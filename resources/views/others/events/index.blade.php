@extends('layouts.app')

@section('css')
<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
        border-radius: 0px;
    }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">    
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Others /</span> {{ $meta_title }}</h4>

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
                            <div class="card-datatable table-event">
                                <table class="datatables-basic datatables-event table border-top table-stripedc table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>  
                        </div>

                    </div>
                </div>

                <x-footer /> 
                <div class="content-backdrop fade"></div>
                <div class="layout-overlay layout-menu-toggle"></div>
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="event_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-calendar-plus fs-2 text-white"></i> 
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white">Create Event</h5>
                <span class="text-white slogan">Create New Event</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-event-form />
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    const quillEvent  = new Quill('#description-editor', {
        theme: 'snow',
        placeholder: 'Type event description here...',
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
        $('.flatpickr-input').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        var eventTable = $('.datatables-event');

        if(eventTable.length){
            eventTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: "GET",
                    url: "{{ route('others.events.index') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'row', name: 'No' },
                    { data: 'eventTitle', name: 'Title' },
                    { data: 'eventDate', name: 'Date' },
                    { data: 'description', name: 'Description' },
                    { data: 'createdAt', name: 'Created' },
                    { 
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" onclick="openOffcanvas(${row.id})" class="btn btn-sm btn-icon btn-primary"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deleteEvent(${row.id})"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        }

        $('#event-form').on('submit', function (e) {
            e.preventDefault(); 
            $('#description').val(quillEvent.root.innerHTML);
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
                    toastr["success"](response.message);
                    form.trigger('reset');
                    quillEvent.root.innerHTML = ''; 
                    const offcanvasElement = document.getElementById('event_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) offcanvas.hide();
                    eventTable.DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    let message = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors).join('\n');
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr["error"](message);
                },
                complete: function () {
                    form.find('button[type="submit"]').prop('disabled', false).text('Save');
                }
            });
        });
    });

    function openOffcanvas(id = null) {
        const $form = $('#event-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white">Create Event</h5><span class="text-white slogan">Create New Event</span>`);

        const offcanvasElement = $('#event_offcanvas');
        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            const url = "{{ route('others.events.edit', ':event') }}".replace(':event', id);
            $('#target_id').val(id);
            $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white">Edit Event</h5><span class="text-white slogan">Edit Event</span>`);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#eventTitle').val(data.event.eventTitle);
                    $('#eventDate').flatpickr().setDate(data.event.eventDate, true);
                    const desc = data.event.description || '';
                    quillEvent.root.innerHTML = desc;
                    $('#description').val(desc);
                },
                error: function () {
                    alert('Failed to load event data.');
                }
            });
        }
    }

    function deleteEvent(id) {
        if (confirm('Are you sure you want to delete this event?')) {
            $.ajax({
                url: "{{ route('others.events.destroy', ':event') }}".replace(':event', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatables-event').DataTable().ajax.reload();
                },
                error: function() {
                    alert("Error deleting event. Please try again.");
                }
            });
        }
    }
</script>
@endpush
