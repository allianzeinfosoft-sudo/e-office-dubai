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
                                        <table class="datatables-basic datatables-login-history table border-top table-stripedc table-hover table-striped" id="datatables-login-history">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>User</th>
                                                    <th>IP Address</th>
                                                    <th>Login At</th>
                                                    <th>Logout At</th>
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

    let loginHistoryTable = $('.datatables-login-history');

        if (loginHistoryTable.length) {
            loginHistoryTable.DataTable({
                dom: 'Bfrtip',
                buttons: ['excelHtml5', 'pdfHtml5', 'print'],
                ajax: {
                    type: "GET",
                    url: "{{ route('settings.login-history') }}",
                    dataType: "json",
                    data: function (d) {
                        d.username = $('#username').val();
                    },
                    dataSrc: "data"
                },
                processing: true,
                columns: [
                    {
                        data: null,
                        title: 'Sl No',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'username', title: 'User' },
                    { data: 'ip_address', title: 'IP Address' },
                    { data: 'login_at', title: 'Login At' },
                    { data: 'logout_at', title: 'Logout At' },
                ]
            });

            $('#filter-form').on('submit', function (e) {
                e.preventDefault();
                loginHistoryTable.DataTable().ajax.reload();
            });
        }



    });


</script>
@endpush
