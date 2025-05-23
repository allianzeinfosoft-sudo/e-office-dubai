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
                            <div class="card card-bg">
                                <div class="card-body">
                                    @if(isset($mergedData) && count($mergedData))
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="accordion mt-3" id="accordionExample">
                                                    @foreach ($mergedData as $index => $data)
                                                        @php
                                                            $employee = \App\Models\Employee::where('user_id', $data['emp_id'])->first();
                                                            $fullName = $employee ? $employee->full_name : 'Unknown';
                                                            $accordionId = 'accordion' . $index;
                                                        @endphp

                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}" aria-expanded="false" aria-controls="{{ $accordionId }}">
                                                                    {{ $fullName }} - {{ $data['report_date'] }}
                                                                </button>
                                                            </h2>
                                                            <hr class="separator m-0">

                                                            <div id="{{ $accordionId }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="table-responsive mb-2">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Signin Time</th>
                                                                                    <th>Signout Time</th>
                                                                                    <th>Working Hours</th>
                                                                                    <th>Punchin Note</th>
                                                                                    <th>Punchout Note</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>{{ $data['signin_time'] ?? 'N/A' }}</td>
                                                                                    <td>{{ $data['signout_time'] ?? 'N/A' }}</td>
                                                                                    <td>{{ $data['working_hours'] ?? 'N/A' }}</td>
                                                                                    <td>{{ $data['punchin_note'] ?? '' }}</td>
                                                                                    <td>{{ $data['punchout_note'] ?? '' }}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Project</th>
                                                                                    <th>Work Type</th>
                                                                                    <th>Work Time</th>
                                                                                    <th>Total Records</th>
                                                                                    <th>Total Time</th>
                                                                                    <th>Productivity Hour</th>
                                                                                    <th>Achieved Hour</th>
                                                                                    <th>Grade (%)</th>
                                                                                    <th>Performance</th>
                                                                                    <th>Comments</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($data['reports'] as $report)
                                                                                    <tr>
                                                                                        <td>{{ $report['project_name'] }}</td>
                                                                                        <td>{{ $report['type_of_work'] }}</td>
                                                                                        <td>{{ $report['time_of_work'] }}</td>
                                                                                        <td>{{ $report['total_records'] }}</td>
                                                                                        <td>{{ $report['total_time'] }}</td>
                                                                                        <td>{{ $report['productivity_hour'] }}</td>
                                                                                        <td>{{ $report['achieved_hour'] }}</td>
                                                                                        <td>{{ $report['grade'] }}%</td>
                                                                                        <td>{{ $report['performance'] }}</td>
                                                                                        <td>{{ $report['comments'] }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-3">
                                            No work reports found for the selected filters.
                                        </div>
                                    @endif

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
                                            
                                        <form id="filter-form" method="POST" action="{{ route('reports.all-work-report') }}">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="employee_id">Select Employee</label>
                                                <select name="employee_id" id="employee_id" class="form-control select2" required>
                                                    <option value="">All Employees</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->user_id }}" >
                                                            {{ $employee->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="month">Month</label>
                                                <select name="month" id="month" class="form-control select2">
                                                    @for ($m = 1; $m <= 12; $m++)
                                                        @php 
                                                            $monthName = \Carbon\Carbon::create()->month($m)->format('F'); 
                                                            $mFormatted = str_pad($m, 2, '0', STR_PAD_LEFT);
                                                        @endphp
                                                        <option value="{{ $mFormatted }}" {{ request('month') == $mFormatted ? 'selected' : '' }}>{{ $monthName }}</option>
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

        $('#report_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

    });

    
</script>
@endpush