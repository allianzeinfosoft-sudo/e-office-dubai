@extends('layouts.app')

@section('css')
<style>
    
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

                        <div class="col-sm-9">

                            <div class="card card-bg">
                                <div class="card-datatable">
                                    <table class="datatables-basic datatables-working-hours-report table border-top table-stripedc table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Month</th>
                                                <th>Avg. Working Hours</th>
                                                <th>Total Working Hours</th>
                                                <th>Month Working Hours / Min Individual Working Hours</th>
                                                <th>Days Worked</th>
                                                <th>No. of Working Days</th>
                                                <th>Leaves</th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="card card-bg mb-4">
                                <div class="card-header">
                                    <h5 class="card-title"> <i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <form action="" method="post"> 
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="month">Select Month</label>
                                            <select name="month" class="form-control select2">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    @php $monthName = carbon\Carbon::create()->month($i)->format('F'); @endphp
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ now()->month == $i ? 'selected' : '' }}>
                                                        {{ $monthName }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="year">Year</label>
                                            <select name="year" id="year" class="form-control select2">
                                                <option value="">Select</option>
                                                @for ($year = now()->year; $year >= 2014; $year--)
                                                    <option value="{{ $year }}" {{ now()->year == $year ? 'selected' : '' }} >{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <button type="button" class="btn btn-primary">Find</button>
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

        $('.datatables-working-hours-report').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("report.data") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'month', name: 'month' },
                { data: 'avg_working_hours', name: 'avg_working_hours' },
                { data: 'total_working_hours', name: 'total_working_hours' },
                { data: 'mwh_miwh_ratio', name: 'mwh_miwh_ratio' },
                { data: 'days_worked', name: 'days_worked' },
                { data: 'working_days', name: 'working_days' },
                { data: 'leaves', name: 'leaves' }
            ]
        });

    });
</script>
@endpush