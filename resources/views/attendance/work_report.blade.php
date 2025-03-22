@extends('layouts.app')

@section('css')
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- Menu section -->
        <x-menu />

        <!-- Page content -->
        <div class="layout-page">

            <!-- Header Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Attendance /</span>{{ $meta_title }}</h4>

                    <div class="row">

                        <div class="col-lg-12 mb-4" id="workReportFormContainer">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <small class="d-block mb-1"> You must be enter your work report</small>
                                    </div>
                                    <h4 class="card-title mb-1"> <i class="ti ti-printer ti-sm"></i> {{ $meta_title }}</h4>
                                </div>
                                <div class="card-body">
                                    <form id="workReportForm" action="{{ route('work-report.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="project_name" class="form-label">Project</label>
                                                    <select name="project_name" id="project_name" data-placeholder="Select Project" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                        @if($projects->isNotEmpty())
                                                            @foreach($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>   

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="type_of_work" class="form-label">Type of Work</label>
                                                    <select name="type_of_work" data-placeholder="Select Type of Work" id="type_of_work" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="total_records" class="form-label">Total Records / Tasks</label>
                                                    <input type="text" name="total_records" id="total_records" placeholder="Totla Records / Tasks" class="form-control" />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="productivity_hour" class="form-label">Productivity Per Hour</label>
                                                    <input type="text" name="productivity_hour" id="productivity_hour" placeholder="Productivity per hour" class="form-control" />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="total_time" class="form-label">No. of Hours</label>
                                                    <input type="time" name="total_time" id="total_time" placeholder="No. of Hours" value="{{ date('H:i:s', strtotime($missingReport->working_hours)) }}" class="form-control" required />
                                                </div>
                                            </div>    

                                            <div class="col-sm-12 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="comments" class="form-label">Comments</label>
                                                    <textarea name="comments" id="comments" class="form-control" rows="5"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12 mb-2 g-2 d-flex justify-content-end">
                                                <input type="hidden" name="emp_id" value="{{ $missingReport->emp_id }}" />
                                                <input type="hidden" name="report_date" value="{{ $missingReport->signin_date }}" />
                                                <button type="button" id="submitForm" class="btn btn-primary"><i class="ti ti-plus"></i> Add</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        
                                    </div>
                                    <h4 class="card-title mb-1"> <i class="ti ti-printer ti-sm"></i> Work Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 mb-2">
                                            <div class="table-responsive">
                                                <table id="workReportTable" class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="align-middle" width="11.11%">Project Name</th>
                                                            <th class="align-middle" width="11.11%">Type of Work</th>
                                                            <th class="align-middle" width="11.11%">Total Records / Tasks</th>
                                                            <th class="align-middle" width="11.11%">No. of Hours</th>
                                                            <th class="align-middle" width="11.11%">Productivity / Hour</th>
                                                            <th class="align-middle" width="11.11%">Grade</th>
                                                            <th class="align-middle" width="11.11%">Performance</th>
                                                            <th class="align-middle" width="17.22%">Comments</th>
                                                            <th class="align-middle" width="5%">Action</th>
                                                        </tr>                                                    
                                                    </thead>
                                                    
                                                    <tbody >

                                                        @if($missingReport)
                                                        <tr>
                                                            <td><strong>Break</strong></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td>{{ $missingReport->break_time }}</td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td>Auto Break</td>
                                                            <td><button type="button" class="btn btn-icon btn-warning waves-effect"><i class="ti ti-edit"></i></button></td>
                                                        </tr>
                                                        @endif

                                                    </tbody> 
                                                </table>
                                            </div>
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
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    $(function() {
        $('.select2').select2();

        $('#project_name').on('change', function () {
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
                                options += `<option value="${task.id}">${task.task_name}</option>`;
                            });
                            $('#type_of_work').html(options);
                        }else{
                            $('#type_of_work').html('<option value="">Select a task</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#submitForm').on('click', function(e){
            e.preventDefault(); // Prevent default form submission
            let formData = $('#workReportForm').serialize(); // Serialize form data

            $.ajax({
                type: "POST",
                url: $('#workReportForm').attr('action'), 
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Work report added successfully!');
                        $('#workReportForm')[0].reset(); 
                        getReportData(response.data);
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

    });

    function getReportData(workReport) {
        if (!workReport) return;
        var html = '';
        html += '<tr>';
        html += "<td><strong>" + workReport.project_name + "</strong></td>";
        html += "<td><strong>" + workReport.type_of_work + "</strong></td>";
        html += "<td><strong>" + workReport.total_records + "</strong></td>";
        html += "<td><strong>" + workReport.time_of_work + "</strong></td>";
        html += "<td><strong>" + workReport.productivity_hour + "</strong></td>";
        html += "<td><strong>" + (workReport.grade || "-") + "</strong></td>"; // Handle missing grade
        html += "<td><strong>" + (workReport.performance || "-") + "</strong></td>"; // Handle missing performance
        html += "<td><strong>" + (workReport.comments || "No comments") + "</strong></td>";
        html += '<td>';
        html += '<button type="button" class="btn btn-sm btn-icon btn-primary waves-effect"><i class="ti ti-edit"></i></button>';
        html += '<button type="button" class="btn btn-sm btn-icon btn-danger waves-effect"><i class="ti ti-trash"></i></button>';
        html += '</td>';
        html += '</tr>';
        $("#workReportTable tbody").append(html);
    }
</script>
@stop