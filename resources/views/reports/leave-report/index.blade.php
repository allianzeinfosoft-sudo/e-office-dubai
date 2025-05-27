@extends('layouts.app')

@section('css')
<style>
    .dt-buttons{
        float: left;
        margin-top: 10px;
        margin-left: 10px;
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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Report /</span> {{ $meta_title }}</h4>

                    <div class="row">

                        <div class="col-sm-10">

                            <div class="card card-bg">
                                <div class="card-datatable">
                                    <div class="table-responsive">
                                        <table class="datatables-basic datatables-leave-report table border-top table-stripedc table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Username</th>
                                                    <th>From Date</th>
                                                    <th>To Date</th>
                                                    <th>Leave Count</th>
                                                    <th>Type</th>
                                                    <th>Leave Reason</th>
                                                    <th>Apply Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
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
                                                <label for="username">Username</label>
                                                <select name="username" id="username" class="form-control select2">
                                                    <option value="">All</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="start_date">Start Date</label>
                                                <input type="text" id="start_date" name="start_date" class="form-control flatpickr-input" placeholder="Start Date" value="{{ date('Y-m-d') }}"  />
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="end_date">End Date</label>
                                                <input type="text" id="end_date" name="end_date" class="form-control flatpickr-input" placeholder="End Date" value="{{ date('Y-m-d') }}" >
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
@stop


@push('js')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
    $(function () {

        $('#start_date, #end_date').flatpickr({
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'Y-m-d'
    });

    let leaveReportTable = $('.datatables-leave-report');

    if (leaveReportTable.length) {
        leaveReportTable.DataTable({
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print'],
            ajax: {
                type: "GET",
                url: "{{ route('reports.leave-report-data') }}",
                dataType: "json",
                data: function (d) {
                    d.username = $('#username').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
                dataSrc: "data"
            },
            processing: true,
            columns: [
                { data: 'id', title: 'Sl No' },
                { data: 'username', title: 'Username' },
                { data: 'leave_from', title: 'From date' },
                { data: 'leave_to', title: 'To date' },
                { data: 'leave_count', title: 'Leave Count' },
                { data: 'leave_type', title: 'Type' },
                { data: 'reason', title: 'Leave Reason' },
                { data: 'apply_date', title: 'Apply Date' },
                { data: 'status', title: 'Status' },
                { data: null, title: 'Action',
                    render: function (data, type, row) {
                        //return '<a href="/leaves/' + row.id + '/edit" class="btn btn-sm btn-primary"><i class="ti ti-edit"></i></a>';
                        return '<a href="/leaves" class="btn btn-sm btn-primary"><i class="ti ti-edit"></i></a>';
                    }
                },
            ]
        });

        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            leaveReportTable.DataTable().ajax.reload();
        });
    }

    });


</script>
@endpush
