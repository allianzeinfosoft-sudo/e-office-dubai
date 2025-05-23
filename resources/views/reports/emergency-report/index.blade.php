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
                                        <table id="reportTable" class="table table-bordered table-striped table-hover table-sm display " style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Signin Date</th>
                                                <th>Signin Time</th>
                                                <th>Signout Time</th>
                                                <th>Working Hours</th>
                                                <th>Signin Note</th>
                                                <th>Signout Note</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody id="reportContainer">
                                                <tr>
                                                    <td class="text-center" colspan="9">
                                                        <div class="alert alert-warning mt-3">
                                                            No emergency attendance reports found for the selected filters.
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
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
                                            
                                        <form id="filter-form" method="post">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="month">Month</label>
                                                <select name="month" id="month" class="form-control select2">
                                                    @for ($m = 1; $m <= 12; $m++)
                                                        @php 
                                                            $monthName = \Carbon\Carbon::create()->month($m)->format('F'); 
                                                            $mFormatted = str_pad($m, 2, '0', STR_PAD_LEFT);
                                                        @endphp
                                                        <option value="{{ $mFormatted }}" {{ now()->month == $m ? 'selected' : '' }} >{{ $monthName }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="year">Year</label>
                                                <select name="year" id="year" class="form-control select2">
                                                    @for ($y = now()->year; $y >= 2014; $y--)
                                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                    @endfor
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
<script>
    $(function () {

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: '/reports/get-emergency-attendance', // Your Laravel route
                method: 'POST',
                data: formData,
                success: function(response) {
                        // Destroy existing DataTable if any
                    if ($.fn.DataTable.isDataTable('#reportTable')) {
                        $('#reportTable').DataTable().clear().destroy();
                    }
                    
                    let html = ``;
                    response.forEach(row => {
                        html += `<tr>
                                <td>${row.username}</td>
                                <td>${row.signin_date}</td>
                                <td>${row.signin_time}</td>
                                <td>${row.signout_time}</td>
                                <td>${row.working_hours}</td>
                                <td>${row.signin_late_note}</td>
                                <td>${row.signout_late_note}</td>
                                <td>${row.status}</td>
                            </tr>`;
                    });

                    $('#reportContainer').html(html);

                    $('#reportTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            { extend: 'excelHtml5', title: 'Emergency Attendance Report'},
                            { extend: 'pdfHtml5', title: 'Emergency Attendance Report', orientation: 'landscape', pageSize: 'A4'},
                            { extend: 'print', title: 'Emergency Attendance Report'}
                            ],
                        responsive: true
                    });
                },
                error: function(xhr) {
                    alert("Failed to fetch report data.");
                }
            });
        });

    });

    
</script>
@endpush