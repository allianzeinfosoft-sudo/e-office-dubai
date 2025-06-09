@extends('layouts.app')

@section('css')
<style>
    .dt-buttons{
        float: left;
        margin-top: 2px;
        margin-left: 10px;
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
                        <div class="col-sm-10">
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
                                            </tr>
                                        </thead>
                                    </table>
                                </div>  
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="card card-bg mb-4">
                                <div class="card-header">
                                    <h5 class="card-title"> <i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            
                                            <form id="filter-form"> 
                                                @csrf
                                                <div class="form-group mb-3">
                                                    <label for="from_date">From Date</label>
                                                    <input type="text" name="from_date" class="form-control flatpickr-input" id="from_date" placeholder="Select Date" value="{{ now()->format('d-m-Y') }}">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="to_date">To Date</label>
                                                    <input type="text" name="to_date" class="form-control flatpickr-input" id="to_date" placeholder="Select Date" value="{{ now()->format('d-m-Y') }}">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="department_id">Department</label>
                                                    <select class="form-select  form-control select2" name="department_id" id="department_id">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $result)
                                                            <option value="{{ $result->id }}"> {{ $result->department }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="booked_by">Booked By</label>
                                                    <select class="form-select  form-control select2" name="booked_by" id="booked_by">
                                                        <option value="">Select Employee</option>
                                                        @foreach($employees as $result)
                                                            <option value="{{ $result->user_id }}"> {{ $result->full_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
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

        $("#from_date, #to_date").flatpickr({
                monthSelectorType: 'static',
                altInput: true,
                altFormat: 'd-m-Y',
                dateFormat: 'd-m-Y',
            });

        var conferanceTable = $('.datatables-conferance');

        if (conferanceTable.length) {
            
            conferanceTable.DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excelHtml5', title: 'Conferance Hall Booking Report', 
                        //exportOptions: {
                            //columns: [0, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude profile image (index 0)
                        //}
                    },
                    { extend: 'pdfHtml5', title: 'Conferance Hall Booking Report', orientation: 'landscape', pageSize: 'A4', 
                        //exportOptions: {
                            //columns: [0, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude profile image (index 0)
                        //}
                    },
                    { extend: 'print', title: 'Conferance Hall Booking Report', 
                        //exportOptions: {
                            //columns: [0, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude profile image (index 0)
                        //}
                    }
                ],
                processing: true,
                serverSide: false,
                ajax: {
                    type: "GET",
                    url: "{{ route('conferance-hall.report-data') }}",
                    data: function (d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.department_id = $('#department_id').val();
                        d.booked_by = $('#booked_by').val();
                    },
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
                ],
            });
        }

        
         // Reload table on form submit
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            conferanceTable.DataTable().ajax.reload();
        });
    });

    

    

    


</script>
@endpush
