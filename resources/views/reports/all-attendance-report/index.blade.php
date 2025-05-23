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

                        <div class="col-sm-10" >
                            <div id="all-report-container" >
                                <div class="card card-bg">
                                    <div class="card-body">
                                        <p class="text-center">No data found</p>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card card-bg">
                                        <div class="card-header">
                                            <h5 class="card-title"> <i class="ti ti-filter ti-sm"></i> Avarage working hours</h5>
                                        </div>
                                        <div class="card-body">
                                            <x-charts.apex-bar-chart
                                                element-id="barChart"
                                                :series="[
                                                    ['name' => 'Apple', 'data' => [90, 120, 55, 100, 80, 125, 175, 70, 88, 180]],
                                                    ['name' => 'Samsung', 'data' => [85, 100, 30, 40, 95, 90, 30, 110, 62, 20]]
                                                ]"
                                                :categories="['7/12', '8/12', '9/12', '10/12', '11/12', '12/12', '13/12', '14/12', '15/12', '16/12']"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div> -->

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
                                                        <label for="employee_id">Select Employee</label>
                                                        <select name="employee_id" id="employee_id" class="form-control select2" required >
                                                            <option value="">All Employees</option>
                                                            @foreach ($employees as $employee)
                                                                <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="day">Day</label>
                                                        <select name="day" id="day" class="form-control select2">
                                                            <option value="">All Days</option>
                                                            @for ($d = 1; $d <= 31; $d++)
                                                                <option value="{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}">{{ $d }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="month">Month</label>
                                                        <select name="month" id="month" class="form-control select2">
                                                            @for ($m = 1; $m <= 12; $m++)
                                                                @php $monthName = \Carbon\Carbon::create()->month($m)->format('F'); @endphp
                                                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ now()->month == $m ? 'selected' : '' }} >{{ $monthName }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="year">Year</label>
                                                        <select name="year" id="year" class="form-control select2">
                                                            @for ($y = now()->year; $y >= 2014; $y--)
                                                                <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }} >{{ $y }}</option>
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
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
    $(function () {

        $('#report_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });
        
        // Reload table on form submit
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            var url = "{{ route('reports.all-attendance-data') }}";
            var data = $(this).serialize();
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: "json",
                success: function (response) {
                    $('#all-report-container').html(response.html);
                    $('.datatables-all-attendance-report').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            { extend: 'excelHtml5', title: 'All Attendance Report'},
                            { extend: 'pdfHtml5', title: 'All Attendance Report', orientation: 'landscape', pageSize: 'A4'},
                            { extend: 'print', title: 'All Attendance Report'}
                        ],
                    });
                }
            });
        });

    });

    
</script>
@endpush