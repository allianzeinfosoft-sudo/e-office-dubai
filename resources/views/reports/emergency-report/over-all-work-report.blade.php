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
                                    <h5 class="card-title"> <i class="ti ti-filter ti-sm"></i> Filter</h5>
                                </div>
                                <div class="card-body">
                                    <form id="filter-form" method="POST" onsubmit="return false;">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label for="employee_id">Select Employee</label>
                                                    <select name="employee_id" id="employee_id" class="form-control select2">
                                                        <option value="">All Employees</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->user_id }}" >
                                                                {{ $employee->full_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label for="project_id">Select Project</label>
                                                    <select name="project_id" id="project_id" class="form-control select2">
                                                        <option value="">All Projects</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label for="task_id">Select Task</label>
                                                    <select name="task_id" id="task_id" class="form-control select2">
                                                        <option value="">All Tasks</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label for="day">Day</label>
                                                    <select name="day" id="day" class="form-control select2">
                                                        <option value="">All Days</option>
                                                        @for ($d = 1; $d <= 31; $d++)
                                                            <option value="{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}">{{ $d }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label for="month">Month</label>
                                                    <select name="month" id="month" class="form-control select2">
                                                        @for ($m = 1; $m <= 12; $m++)
                                                            @php 
                                                                $monthName = \Carbon\Carbon::create()->month($m)->format('F'); 
                                                                $mFormatted = str_pad($m, 2, '0', STR_PAD_LEFT);
                                                            @endphp
                                                            <option value="{{ $mFormatted }}" {{  now()->month == $m ? 'selected' : '' }}  @endphp >{{ $monthName }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group mb-3">
                                                    <label for="year">Year</label>
                                                    <select name="year" id="year" class="form-control select2">
                                                        @for ($y = now()->year; $y >= 2014; $y--)
                                                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Find</button>
                                                </div>     
                                            </div>
                                        </div>
                                    </form>                                            
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" >
                            <div class="card card-bg">
                                <div class="card-datatable">
                                    <div class="row p-2">
                                        <div class="col-sm-12 table-responsive">
                                            <table id="reportTable" class="table table-bordered table-striped table-hover table-sm display nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Project</th>
                                                        <th>Type of Work</th>
                                                        <th>Time of Work</th>
                                                        <th>Total Time</th>
                                                        <th>Comments</th>
                                                        <th>Report Date</th>
                                                        <th>Total Records</th>
                                                        <th>Productivity Hour</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="reportContainer">
                                                    <tr>
                                                        <td class="text-center" colspan="10">
                                                            <div class="alert alert-warning mt-3">
                                                                No work reports found for the selected filters.
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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

        $('#employee_id').on('change', function () {
            let employeeId = $(this).val();

            if (employeeId) {
                $.ajax({
                    url: '/reports/get-projects-by-employee',
                    data: { employee_id: employeeId },
                    success: function (projects) {
                        let options = '<option value="">Select Project</option>';
                        projects.forEach(project => {
                            options += `<option value="${project.id}">${project.project_name}</option>`;
                        });
                        $('#project_id').html(options);
                    }
                });
            } else {
                $('#project_id').html('<option value="">Select Project</option>');
                $('#taksk_id').html('<option value="">Select a task</option>');
            }
        });

        $('#project_id').on('change', function () {
            let project_id = $(this).val();
            if (project_id) {
                let url = `{{ route('tasks-project.get-tasks-by-project', ':project_id') }}`.replace(':project_id', project_id);

                $.ajax({
                    type: "GET",
                    url: url, // ✅ Removed incorrect semicolon
                    success: function (response) {
                        if (response.success) {
                            let options = '<option value="">Select a task</option>';
                            response.data.forEach(task => {
                                options += `<option value="${task.tasks.id}">${task.tasks.name}</option>`;
                            });
                            $('#task_id').html(options);
                        }else{
                            $('#taksk_id').html('<option value="">Select a task</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: '/reports/get-emergency-reports', // Your Laravel route
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
                                <td>${row.project.project_name}</td>
                                <td>${row.tasks.name}</td>
                                <td>${row.time_of_work}</td>
                                <td>${row.total_time}</td>
                                <td>${row.comments}</td>
                                <td>${row.report_date}</td>
                                <td>${row.total_records}</td>
                                <td>${row.productivity_hour}</td>
                            </tr>`;
                    });

                    $('#reportContainer').html(html);

                    $('#reportTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: ['excel', 'pdf', 'print'],
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