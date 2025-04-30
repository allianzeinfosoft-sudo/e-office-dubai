@extends('layouts.app')

@section('css')

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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Attendance /</span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div class=" float-end mt-15 mr-20"></div>

                            <table class="datatables-basic datatables-custom-attendance table border-top table-stripedc table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th><i class="ti ti-users"></i></th>
                                        <th>Username</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>

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
<script>
    $(function() {
        var customAttendance = $('.datatables-custom-attendance');

        if (customAttendance.length) {
            customAttendance.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('custom-attendance.index') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: null,
                        title: 'Sl. No.',
                        render: function (data, type, row, meta) {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                    },
                    {
                        data: 'profile_image',
                        title: '<i class="ti ti-users"></i>',
                        render: function (data, type, row) {
                            return `<img src="/storage/${data}" alt="User" class="rounded-circle" width="40" height="40">`;
                        }
                    },
                    { data: 'username', title: 'Username' },
                    { data: 'signin_date', title: 'Date' },
                    { data: 'signin_time', title: 'Mark-In Time' },
                    { data: 'reason', title: 'Reason' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="/custom-attendance/accept-custom-mark-in/${row.id}" class="btn btn-sm btn-success"><i class="ti ti-check"></i> Accept </a>
                                <a href="/custom-attendance/reject-custom-mark-in/${row.id}" class="btn btn-sm btn-danger"> Reject <i class="ti ti-ban"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

</script>
@endpush