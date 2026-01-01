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
                                        <table class="datatables-basic datatables-training-attendance-report table border-top table-stripedc table-hover table-striped" id="datatables-training-attendance-report">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Training Title</th>
                                                    <th>Employee Name</th>
                                                    <th>Acceptance Status</th>
                                                    <th>Attendance</th>
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
                                                <label for="trainings">Trainings</label>
                                                <select name="trainings" id="trainings" class="form-control select2">
                                                    <option value="">All</option>
                                                    @foreach ($trainings as $training)
                                                        <option value="{{ $training->training_title }}">{{ $training->training_title }}</option>
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

    let attendanceReportTable = $('.datatables-training-attendance-report');

    if (attendanceReportTable.length) {
        attendanceReportTable.DataTable({
            dom: 'Bfrtip',
            processing: true,
            ajax: {
                type: "GET",
                url: "{{ route('training.attendance-report-data') }}",
                dataType: "json",
                data: function (d) {
                    d.trainings = $('#trainings').val();
                },
                dataSrc: "data"
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Training Attendance Report',
                    filename: function () {
                        let training = $('#trainings').val() || 'All_Trainings';
                        return 'Training_Attendance_Report_' + training.replace(/\s+/g, '_');
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Training Attendance Report',
                    filename: function () {
                        let training = $('#trainings').val() || 'All_Trainings';
                        return 'Training_Attendance_Report_' + training.replace(/\s+/g, '_');
                    },
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    title: 'Training Attendance Report'
                }
            ],
            columns: [
                { data: 'id', title: 'Sl No' },
                { data: 'training_title', title: 'Training Title' },
                { data: 'employee_name', title: 'Employee Name' },
                { data: 'acceptance_status', title: 'Acceptance Status' },
                { data: 'attendance', title: 'Attendance' }
            ]
        });

        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            attendanceReportTable.DataTable().ajax.reload();
        });
    }

});
</script>
@endpush

