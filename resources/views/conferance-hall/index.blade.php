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
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">    
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Conferance Hall /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openConferanceOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New Booking</span>
                                </span>
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-conferance" style="font-size: 11px;">
                                <table class="datatables-basic datatables-conferance table border-top table-hover table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Booked By</th>
                                            <th>Department</th>
                                            <th>Participants</th>
                                            <th>Purpose</th>
                                            <th>Status</th>
                                            <th width="14%">Actions</th>
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

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="conferance_hall_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="conferance-hall-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Booking</h5>
                <span class="text-white slogan">Create New conferance hall booking</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body" style="overflow: visible !important;">
        <div class="row">
            <div class="col-sm-12">
                <x-conferance-hall-booking-form />
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    
    $(function(){

        var conferanceTable = $('.datatables-conferance');

        if (conferanceTable.length) {
            
            conferanceTable.DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    type: "GET",
                    url: "{{ route('conferance-hall.index') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'row', name: 'No' },
                    { data: 'booking_date', name: 'Date' },
                    { data: 'start_time', name: 'Start Time' },
                    { data: 'end_time', name: 'End Time' },
                    { data: 'booked_by', name: 'Booked By' },
                    { data: 'department', name: 'Department' },
                    { data: 'participants', name: 'Participent' },
                    { data: 'purpose', name: 'Purpose' },
                    { data: 'status', name: 'Status' },
                    { 
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" onclick="openConferanceOffcanvas(${row.id})" class="btn btn-sm btn-icon btn-primary"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deleteConferanceHall(${row.id})"><i class="ti ti-trash"></i></button>`;
                        }
                    }
                ],
            });
        }

        $('#coferance_hall_form').on('submit', function (e) {
            e.preventDefault();

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
                    const offcanvasElement = document.getElementById('conferance_hall_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) offcanvas.hide();
                    conferanceTable.DataTable().ajax.reload(null, false);
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

    function openConferanceOffcanvas(id = null) {
        const $form = $('#coferance_hall_form');
        $form[0].reset();
        
        $('#booking_date').flatpickr().setDate(null, true);
        $('#start_time').val('');
        $('#end_time').val('');
        $('#department_id').val('').trigger('change');
        $('#booked_by').val('').trigger('change');
        $('#participants').val('').trigger('change');
        $('#purpose').val('');

        $('#target_id').val('');
        $('#conferance-hall-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create Booking</h5>
                <span class="text-white slogan">Create New conferance hall booking</span>`);

        const offcanvasElement = $('#conferance_hall_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            const url = "{{ route('conferance-hall.edit', ':conferenceHall') }}".replace(':conferenceHall', id);

            
            $('#target_id').val(id);
            $('#conferance-hall-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit Conferance Hall</h5><span class="text-white slogan">Edit Booking </span>`);
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                        console.log(data);
                        $('#booking_date').flatpickr().setDate(data['conference_hall'].booking_date, true);
                        $('#start_time').val(data['conference_hall'].start_time);
                        $('#end_time').val(data['conference_hall'].end_time);
                        $('#department_id').val(data['conference_hall'].department_id).trigger('change');
                        $('#booked_by').val(data['conference_hall'].booked_by).trigger('change');
                        $('#participants').val(data['conference_hall'].participants.split(',')).trigger('change');
                        $('#purpose').val(data['conference_hall'].purpose);
                },
                error: function () {
                    alert('Failed to load MOM data.');
                }
            });
        }
    }

    function deleteConferanceHall(id) {
        if (confirm('Are you sure you want to delete this Booking?')) {
            $.ajax({
                url: "{{ route('conferance-hall.destroy', ':conferenceHall') }}".replace(':conferenceHall', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatables-conferance').DataTable().ajax.reload();
                },
                error: function() {
                    alert("Error deleting Conferance Hall. Please try again.");
                }
            });
        }
    }


</script>
@endpush
