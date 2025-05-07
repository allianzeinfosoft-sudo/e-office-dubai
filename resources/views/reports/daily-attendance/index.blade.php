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

                                        <table class="datatables-basic datatables-working-hours-report table border-top table-stripedc table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><i class="ti ti-user ti-sm"></i></th>
                                                    <th>Name</th>
                                                    <th>Signin Time</th>
                                                    <th>Signout Time</th>
                                                    <th>Break Time</th>
                                                    <th>Working Hours</th>
                                                    <th>Signin Note</th>
                                                    <th>Signout Note</th>
                                                    <th>Status</th>
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
                                                    <label for="report_date">Select Date</label>
                                                    <input type="text" name="report_date" class="form-control flatpickr-input" id="report_date" placeholder="Select Date" value="{{ now()->format('d-m-Y') }}">
                                                </div>

                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Find</button>
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

        $('#report_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });
        
        let workingHoursTable = $('.datatables-working-hours-report');

        if (workingHoursTable.length) {
            $('.datatables-working-hours-report').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excelHtml5', title: 'Daily Attendance Report', 
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude profile image (index 0)
                        }
                    },
                    { extend: 'pdfHtml5', title: 'Daily Attendance Report', orientation: 'landscape', pageSize: 'A4', 
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude profile image (index 0)
                        }
                    },
                    { extend: 'print', title: 'Daily Attendance Report', 
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7, 8, 9] // Exclude profile image (index 0)
                        }
                    }
                ],
                ajax: {
                    type: "GET",
                    url: "{{ route('reports.daily-attendance') }}",
                    data: function(d) {
                        d.report_date = $('#report_date').val();
                    },
                    dataSrc: "data"
                },
                columns: [
                    { data: 'index', title: '#' },
                    { data: 'image', title: '<i class="ti ti-user ti-sm"></i>', orderable: false, searchable: false },
                    { data: 'name', title: 'Name' },
                    { data: 'signin_time', title: 'Signin Time'},
                    { data: 'signout_time', title: 'Signout Time' },
                    { data: 'break_time', title: 'Break Time' },
                    { data: 'working_hours', title: 'Working Hours' },
                    { data: 'signin_note', title: 'Signin Note' },
                    { data: 'signout_note', title: 'Signout Note' },
                    { data: 'status', title: 'Status' }
                ]
            });
        }

        // Reload table on form submit
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            workingHoursTable.DataTable().ajax.reload();
        });

    });

    
</script>
@endpush