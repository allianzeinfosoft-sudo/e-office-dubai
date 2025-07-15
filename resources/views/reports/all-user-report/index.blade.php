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
                        <div class="col-sm-12">
                            <div class="card card-bg mb-4">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <form id="filter-form">
                                        @csrf
                                        <div class="row align-items-end">
                                            <div class="col-md-4 mb-3">
                                                <label for="start_date">Joining Date Start</label>
                                                <input type="text" id="start_date" name="start_date" class="form-control flatpickr-input" placeholder="Start Date"  />
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="end_date">Joining Date End</label>
                                                <input type="text" id="end_date" name="end_date" class="form-control flatpickr-input" placeholder="End Date"  />
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                    <button type="button" class="btn btn-secondary" id="reset-form">Reset</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-bg">
                                <div class="card-datatable">
                                    <div class="table-responsive">
                                        <table class="datatables-basic datatables-all-user-report table border-top table-stripedc table-hover table-striped" id="datatables-all-user-report">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Name</th>
                                                    <th>EMP ID</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Role</th>
                                                    <th>Group</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Joining Date</th>
                                                </tr>
                                            </thead>

                                        </table>
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

    const from_date = $('#start_date').val();
    const to_date = $('#end_date').val();

    let leaveReportTable = $('.datatables-all-user-report');

    if (leaveReportTable.length) {
        leaveReportTable.DataTable({
            dom: 'Bfrtip',
            buttons: [
                        { extend: 'excelHtml5', title: 'All User Report' + from_date + ' to ' + to_date},
                        { extend: 'pdfHtml5', title: 'All User Report' + from_date + ' to ' + to_date},
                        { extend: 'print', title: 'All User Report' + from_date + ' to ' + to_date}
                     ],
            ajax: {
                type: "GET",
                url: "{{ route('reports.all-user-data') }}",
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
                { data: 'name', title: 'Name' },
                { data: 'emp_id', title: 'EMP ID' },
                { data: 'email', title: 'Email' },
                { data: 'phone', title: 'Phone' },
                { data: 'role', title: 'Role' },
                { data: 'group', title: 'Group' },
                { data: 'department', title: 'Department' },
                { data: 'designation', title: 'Designation' },
                { data: 'joining_date', title: 'Joining Date' },
            ]
        });

        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            leaveReportTable.DataTable().ajax.reload();
        });
    }



    });



    document.getElementById('reset-form').addEventListener('click', function () {
        const form = document.getElementById('filter-form');
        form.reset();

        // If using Flatpickr, also clear its value explicitly
        if (window.flatpickr) {
            flatpickr('#start_date', {}).clear();
            flatpickr('#end_date', {}).clear();
        }

        // Or if already initialized and stored:
        // startPicker.clear();
        // endPicker.clear();
    });

</script>
@endpush
