@extends('layouts.app')

@section('css')
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">

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
                                        <small class="d-block mb-1"> You must be enter your work report on {{ date('d-m-Y', strtotime($missingReport->signin_date)) ?? '' }} </small>
                                        <p class="text-danger text-bold">Your balance time : <span class="badge bg-label-danger">{{ date('H:i', strtotime($missingReport->balance_time)) }}</span></p>
                                    </div>
                                    <h4 class="card-title mb-1"> <i class="ti ti-printer ti-sm"></i> {{ $meta_title }}</h4>
                                </div>
                                <div class="card-body">
                                    <form id="workReportForm" action="{{ route('work-report.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="work_report_id" id="work_report_id">
                                        <input type="hidden" name="_method" id="formMethod" value="POST">

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
                                                    <input type="text" name="productivity_hour" id="productivity_hour" placeholder="Productivity per hour" class="form-control" readonly />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="total_time" class="form-label">No. of Hours</label>
                                                    <input type="text" name="total_time" id="total_time" placeholder="No. of Hours" value="{{ date('H:i', strtotime($missingReport->balance_time)) }}" class="form-control" required />
                                                </div>
                                            </div>    

                                            <div class="col-sm-12 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="comments" class="form-label">Comments <span class="text-danger">*</span></label>
                                                    <textarea name="comments" id="comments" class="form-control" rows="5" required></textarea>
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
                                                            <th class="align-middle" width="10%">Project Name</th>
                                                            <th class="align-middle" width="10%">Type of Work</th>
                                                            <th class="align-middle" width="10%">Total Records / Tasks</th>
                                                            <th class="align-middle" width="10%">No. of Hours</th>
                                                            <th class="align-middle" width="10%">Productivity / Hour</th>
                                                            <th class="align-middle" width="10%">Grade</th>
                                                            <th class="align-middle" width="10%">Performance</th>
                                                            <th class="align-middle" width="20%">Comments</th>
                                                            <th class="align-middle" width="10%">Action</th>
                                                        </tr>                                                    
                                                    </thead>
                                                    
                                                    <tbody >

                                                        @if($missingReport)
                                                        <tr>
                                                            <td><strong>Break Time</strong></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><input class="form-control" type="text" name="break_time" id="break_time" value="{{ ($missingReport->break_time) ?? $user_shift->mini_break_time }}"></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td><span class="badge bg-dark">NA</span></td>
                                                            <td>Auto Break</td>
                                                            <td><button type="button" class="btn btn-sm  btn-success waves-effect" onclick="update_brake_time({{ $missingReport->id }})"><i class="fa fa-save me-2"></i> Update </button></td>
                                                        </tr>
                                                        @endif

                                                        @if($repots_posted ->isNotEmpty())
                                                            @foreach($repots_posted as $report)
                                                            <tr data-id="{{ $report->id }}">                                                            
                                                                <td><strong>{{ $report->project->project_name?? "-" }}</strong></td>
                                                                <td><strong>{{ $report->tasks->name ?? "-"}}</strong></td>
                                                                <td><strong>{{ $report->total_records ?? "-" }}</strong></td>
                                                                <td><strong>{{ $report->total_time ?? "-" }}</strong></td>
                                                                <td><strong>{{ $report->productivity_hour ?? "-" }}</strong></td>
                                                                <td><strong>{{ $report->grade ?? "-" }}</strong></td>
                                                                <td><strong>{{ $report->performance ?? "-" }}</strong></td>
                                                                <td><strong>{{ $report->comments }}</strong></td>
                                                                <td>
                                                                    <button type="button"  class="btn btn-sm btn-icon btn-primary waves-effect" onclick="editReport({{ $report->id }})" ><i class="ti ti-edit"></i></button>
                                                                    <button type="button" class="btn btn-sm btn-icon btn-danger waves-effect" onclick="deleteReport({{ $report->id }})"><i class="ti ti-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        @endif

                                                    </tbody> 
                                                </table>
                                            </div>
                                        </div>                                                        
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between"></div>
                                        <div class="d-flex align-items-right justify-content-end">
                                            <button type="button" onclick="submitReport()" class="btn btn-primary waves-effect"><i class="ti ti-save ti-sm"></i> Submit Report</button>
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
                                options += `<option value="${task.tasks.id}">${task.tasks.name}</option>`;
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

            let current_working_hours = '{{ $missingReport->balance_time }}';
            let total_time = $('#total_time').val();

            let currentSeconds = parseTimeToSeconds(current_working_hours);
            let enteredSeconds = parseTimeToSeconds(total_time);

            if (enteredSeconds > currentSeconds) {
                alert('No. of Hours cannot be greater than current working hours');
                $('#total_time').focus();
                return;    
            }

            let form = $('#workReportForm');
            let formData = form.serialize(); // Serialize form data
            let actionUrl = form.attr('action'); // Get dynamic form action (Store or Update)
            let method = $('#formMethod').val(); // POST for Create, PUT for Update
            // let formData = $('#workReportForm').serialize(); // Serialize form data
            $.ajax({
                type: method === "PUT" ? "PUT" : "POST",
                url: actionUrl, 
                data: formData,
                success: function(response) {
                    if (response.success) {
                        
                        alert('Work report ' + (method === "PUT" ? 'updated' : 'added') + ' successfully!');
                        
                        $('#workReportForm')[0].reset(); 
                        let workReport = response.data;
                        updateTableRow(workReport);
                       // getReportData(response.data);

                       if (response.balance_working_hours === "00:00:00") {
                            $("#workReportFormContainer").hide();
                        }else{
                            $("#total_time").val(response.balance_working_hours);
                        }                        

                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        $('#type_of_work').on('change', function() {
            let task_id = $(this).val();
            let project_id = $('#project_name').val();

            if (task_id) {
                let url = `{{ route('work-report.get-productivity-target') }}`;

                $.ajax({
                    type: "post",
                    url: url, 
                    data :{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        task_id: task_id,
                        project_id: project_id
                    },
                    success: function (response) {
                        if(response.success){
                            $('#productivity_hour').val((response.data.rph) ? response.data.rph : 0);
                        }else{
                            $('#productivity_hour').val('0');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });

    });

    function getReportData(workReport) {
        if (!workReport) return;

        // Convert Blade variable to a number (only if this script is inside a Blade template)
        let totalWorkedTime = parseTimeToSeconds("{{ date('H:i:s', strtotime($missingReport->working_hours)) }}");
        // Convert workReport total_time to seconds (Assuming it's in H:i:s format)
        let reportedTime = parseTimeToSeconds(workReport.total_time);
        // Hide form container if the reported time is greater than or equal to total worked time
        if (reportedTime >= totalWorkedTime) {
            $('#workReportFormContainer').hide();
        }

        let html = '<tr data-id="${workReport.id}" >';
        html += `<td><strong>${workReport.project?.project_name || "-"}</strong></td>`;
        html += `<td><strong>${workReport.tasks?.name || "-"}</strong></td>`;
        html += `<td><strong>${workReport.total_records || "-"}</strong></td>`;
        html += `<td><strong>${workReport.total_time || "00:00:00"}</strong></td>`;
        html += `<td><strong>${workReport.productivity_hour || "-"}</strong></td>`;
        html += `<td><strong>${workReport.grade || "-"}</strong></td>`;
        html += `<td><strong>${workReport.performance || "-"}</strong></td>`;
        html += `<td><strong>${workReport.comments || "No comments"}</strong></td>`;
        html += '<td>';
        html += '<button type="button" class="btn btn-sm btn-icon btn-primary waves-effect" onclick="editReport(${workReport.id})" ><i class="ti ti-edit"></i></button> ';
        html += '<button type="button" class="btn btn-sm btn-icon btn-danger waves-effect" onclick="deleteReport(${workReport.id})" data-id="${workReport.id}"><i class="ti ti-trash"></i></button>';
        html += '</td>';
        html += '</tr>';

        $("#workReportTable tbody").append(html);
        
    }

    function updateTableRow(workReport) {
    let existingRow = $(`#workReportTable tbody tr[data-id="${workReport.id}"]`);

    let newRow = `
        <tr data-id="${workReport.id}">
            <td><strong>${workReport.project?.project_name || "-"}</strong></td>
            <td><strong>${workReport.tasks?.name || "-"}</strong></td>
            <td><strong>${workReport.total_records || "-"}</strong></td>
            <td><strong>${workReport.total_time || "00:00:00"}</strong></td>
            <td><strong>${workReport.productivity_hour || "-"}</strong></td>
            <td><strong>${workReport.grade || "-"}</strong></td>
            <td><strong>${workReport.performance || "-"}</strong></td>
            <td><strong>${workReport.comments || "No comments"}</strong></td>
            <td>
                <button type="button" class="btn btn-sm btn-icon btn-primary waves-effect edit-report" onclick="editReport(${workReport.id})">
                    <i class="ti ti-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-danger waves-effect delete-report" onclick="deleteReport(${workReport.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        </tr>`;

    if (existingRow.length) {
        existingRow.replaceWith(newRow); // ✅ Update the existing row
    } else {
        $("#workReportTable tbody").append(newRow); // ✅ Add a new row if it doesn't exist
    }
}

    // Helper function to convert H:i:s time format to seconds
    function parseTimeToSeconds(timeString) {
        if (!timeString) return 0;
        let parts = timeString.split(':').map(Number);
        return parts[0] * 3600 + parts[1] * 60 + (parts[2] || 0);
    }


    function submitReport() {
        window.location.reload();
    }

    function editReport(report_id) {
        $.ajax({
            type: "GET",
            url: `/work-report/${report_id}/edit`,
            success: function(response) {
                if (response.success) {
                    $('#workReportFormContainer').show(); // Show form if hidden

                    let report = response.data;
                    $('#project_name').val(report.project_name).trigger('change');

                    setTimeout(() => {
                        $('#type_of_work').val(report.type_of_work).trigger('change');
                    }, 300);

                    $('#total_records').val(report.total_records);
                    $('#productivity_hour').val(report.productivity_hour);
                    $('#total_time').val(report.total_time);
                    $('#comments').val(report.comments);
                    $('#work_report_id').val(report.id);

                    // Change form action for update
                    $("#workReportForm").attr("action", `/work-report/${report_id}/update`);
                    $("#formMethod").val("PUT"); // Set method to PUT for update
                    $('#submitForm').html('<i class="ti ti-check"></i> Update'); // Update button text
                }
            },
            error: function(xhr) {
                alert("Failed to load report data.");
            }
        });
    }

    function deleteReport(reportId) {
        if (!confirm('Are you sure you want to delete this report?')) return;
        $.ajax({
            type: "DELETE",
            url: `/work-report/${reportId}`,
            data: { 
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel security
            },
            success: function(response) {
                if (response.success) {
                    alert('Work report deleted successfully!');
                    $(`tr[data-id="${reportId}"]`).remove(); // Remove the deleted row from the table
                } else {
                    alert('Failed to delete the report.');
                }
            },
            error: function(xhr) {
                alert('Error deleting report.');
            }
        });
    }

    function update_brake_time(attendance_id) {
        let break_time = $('#break_time').val();
        let url = `{{ route('update-brake-time', ':id') }}`.replace(':id', attendance_id);
        $.ajax({
            type: "get",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                break_time: break_time
            },
            dataType: "json",
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.reload();
                }
            }
        });
    }

    function parseTimeToSeconds(rawTime) {
        if (!rawTime) return 0;

        let timeStr = rawTime.trim()
            .toLowerCase()
            .replace(/[\.\-]/g, ':') // convert `8.30` or `8-30` to `8:30`
            .replace(/\s+/g, ' ')    // normalize spacing

        let isAMPM = timeStr.includes('am') || timeStr.includes('pm');

        // Handle AM/PM with Date
        if (isAMPM) {
            let dateObj = new Date(`1970-01-01T${timeStr}`);
            if (!isNaN(dateObj.getTime())) {
                return dateObj.getHours() * 3600 + dateObj.getMinutes() * 60 + dateObj.getSeconds();
            }
        }

        // Remove AM/PM if malformed
        timeStr = timeStr.replace(/(am|pm)/g, '');

        // Split parts safely
        let parts = timeStr.split(':').map(p => parseInt(p, 10));
        parts = parts.filter(p => !isNaN(p));
        parts = Array(3).fill(0).map((_, i) => parts[i] || 0); // [hh, mm, ss]

        let [hh, mm, ss] = parts;
        return (hh * 3600) + (mm * 60) + ss;
    }

</script>
@stop