<style>
    .wfh-report-container {
        background-color: #fffaf5;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #f9e3d0;
    }
    .wfh-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #5d596c;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    .wfh-title i {
        margin-right: 8px;
    }
    .balance-time {
        color: #ff5b5c;
        font-weight: 600;
        float: right;
    }
    .balance-time-value {
        background: #ffe5e5;
        padding: 2px 8px;
        border-radius: 4px;
    }
    .form-label-sm {
        font-size: 0.75rem;
        text-transform: capitalize;
        color: #8e8b99;
        margin-bottom: 4px;
    }
    .btn-orange {
        background-color: #ff6a06;
        border-color: #ff6a06;
        color: white;
    }
    .btn-orange:hover {
        background-color: #e65f05;
        border-color: #e65f05;
        color: white;
    }
    .wfh-table thead th {
        background-color: #fffaf5 !important;
        color: #8e8b99 !important;
        font-size: 0.7rem !important;
        text-transform: uppercase !important;
        font-weight: 600 !important;
        border-bottom: 1px solid #f9e3d0 !important;
    }
    .wfh-table tbody td {
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .badge-na {
        background-color: #5d596c;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
    }
</style>

<div class="wfh-report-container mb-4">
    <form action="{{ route('work-from-home-attendance.store') }}" method="post" id="work-from-home-attendance-form">
        @csrf
        <input type="hidden" name="id" id="target_id">
        <input type="hidden" name="work_type" value="{{ $type }}">
        
        @if(!$hideDetails)
        <div class="mb-4">
            <h5 class="wfh-title">Attendance Details</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-sm">Employee <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="employee_id" id="{{ $type }}_emp_id" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-sm">Sign-in Date <span class="text-danger">*</span></label>
                    <input type="date" name="signin_date" id="{{ $type }}_signin_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-sm">Sign-out Date <span class="text-danger">*</span></label>
                    <input type="date" name="signout_date" id="{{ $type }}_signout_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label-sm">Sign-in Time <span class="text-danger">*</span></label>
                    <input type="time" name="signin_time" id="{{ $type }}_signin_time" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label-sm">Break Time <span class="text-danger">*</span></label>
                    <input type="time" name="brake_time" class="form-control" id="{{ $type }}_brake_time" value="01:00">
                </div>
                <div class="col-md-4">
                    <label class="form-label-sm">Sign-out Time <span class="text-danger">*</span></label>
                    <input type="time" name="signout_time" id="{{ $type }}_signout_time" class="form-control">
                </div>
            </div>
        </div>
        <hr class="my-4">
        @else
            <input type="hidden" name="employee_id" id="{{ $type }}_emp_id">
            <input type="hidden" name="signin_date" id="{{ $type }}_signin_date">
            <input type="hidden" name="signout_date" id="{{ $type }}_signout_date">
            <input type="hidden" name="signin_time" id="{{ $type }}_signin_time">
            <input type="hidden" name="brake_time" id="{{ $type }}_brake_time" value="01:00">
            <input type="hidden" name="signout_time" id="{{ $type }}_signout_time">
        @endif

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="wfh-title mb-0"><i class="ti ti-printer"></i> Add Work Report</h5>
                <div class="balance-time small">
                    Your balance time : <span class="balance-time-value" id="balance_time_display">00:00</span>
                </div>
            </div>
            
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label-sm">Project</label>
                    <select class="form-select select2-report" id="entry_project" data-placeholder="Select Project">
                        <option value=""></option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-sm">Type of Work</label>
                    <select class="form-select select2-report" id="entry_task" data-placeholder="Select Type of Work">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-sm">Total Records / Tasks</label>
                    <input type="text" id="entry_records" class="form-control form-control-sm" placeholder="Total Records">
                </div>
                <div class="col-md-2">
                    <label class="form-label-sm">Productivity Per Hour</label>
                    <input type="text" id="entry_productivity" class="form-control form-control-sm" placeholder="Productivity" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label-sm">No. of Hours</label>
                    <input type="text" id="entry_hours" class="form-control form-control-sm" placeholder="00:00">
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <label class="form-label-sm">Comments <span class="text-danger">*</span></label>
                    <textarea id="entry_comments" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-orange px-4" onclick="pushReportLine()">
                    <i class="ti ti-plus me-1"></i> Add
                </button>
            </div>
        </div>

        <div class="mt-5">
            <h5 class="wfh-title"><i class="ti ti-printer"></i> Work Report</h5>
            <div class="table-responsive">
                <table class="table table-bordered wfh-table">
                    <thead>
                        <tr>
                            <th>PROJECT NAME</th>
                            <th>TYPE OF WORK</th>
                            <th>TOTAL RECORDS / TASKS</th>
                            <th>NO. OF HOURS</th>
                            <th>PRODUCTIVITY / HOUR</th>
                            <th>GRADE</th>
                            <th>PERFORMANCE</th>
                            <th>COMMENTS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="report_list_body">
                        <!-- Initial Break Time Row -->
                        <tr id="break_time_row">
                            <td class="fw-bold">Break Time</td>
                            <td><span class="badge-na">NA</span></td>
                            <td><span class="badge-na">NA</span></td>
                            <td>
                                <input type="text" name="break_time_manual" class="form-control form-control-sm text-center" value="01:00:00" style="width: 80px;">
                            </td>
                            <td><span class="badge-na">NA</span></td>
                            <td><span class="badge-na">NA</span></td>
                            <td><span class="badge-na">NA</span></td>
                            <td class="text-muted">Auto Break</td>
                            <td>
                                <button type="button" class="btn btn-success btn-xs">
                                    <i class="ti ti-lock"></i> Update
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-orange px-5 py-2 fw-bold">
                    Submit Report
                </button>
            </div>
        </div>
    </form>
</div>

@push('js')
<script>
    var reportLines = [];

    $(document).ready(function() {
        $('.select2-report').select2({
            dropdownParent: $('#wfhOffcanvas')
        });

        $('#entry_project').on('change', function() {
            var projectId = $(this).val();
            if(!projectId) return;
            
            let url = `{{ route('tasks-project.get-tasks-by-project', ':id') }}`.replace(':id', projectId);
            $.get(url, function(response) {
                $('#entry_task').empty().append('<option value=""></option>');
                response.data.forEach(item => {
                    $('#entry_task').append(`<option value="${item.tasks.id}">${item.tasks.name}</option>`);
                });
            });
        });

        $('#entry_task').on('change', function() {
            var taskId = $(this).val();
            var projectId = $('#entry_project').val();
            if(!taskId || !projectId) return;

            let url = `{{ route('work-report.get-productivity-target', ':id') }}`.replace(':id', taskId);
            $.post(url, {
                _token: '{{ csrf_token() }}',
                task_id: taskId,
                project_id: projectId
            }, function(response) {
                $('#entry_productivity').val(response.success ? response.data.rph : '0');
            });
        });
    });

    function pushReportLine() {
        const project = $('#entry_project option:selected');
        const task = $('#entry_task option:selected');
        const records = $('#entry_records').val();
        const productivity = $('#entry_productivity').val();
        const hours = $('#entry_hours').val();
        const comments = $('#entry_comments').val();

        if(!project.val() || !task.val() || !comments) {
            toastr.error('Please fill all required fields');
            return;
        }

        const index = reportLines.length;
        const html = `
            <tr id="report_row_${index}">
                <td>
                    ${project.text()}
                    <input type="hidden" name="reports[${index}][project_id]" value="${project.val()}">
                </td>
                <td>
                    ${task.text()}
                    <input type="hidden" name="reports[${index}][type_of_work]" value="${task.val()}">
                </td>
                <td class="text-center">
                    ${records || '0'}
                    <input type="hidden" name="reports[${index}][total_records]" value="${records}">
                </td>
                <td class="text-center">
                    ${hours || '00:00'}
                    <input type="hidden" name="reports[${index}][total_time]" value="${hours}">
                </td>
                <td class="text-center">
                    ${productivity}
                    <input type="hidden" name="reports[${index}][productivity_hour]" value="${productivity}">
                </td>
                <td class="text-center"><span class="badge bg-label-info">A</span></td>
                <td class="text-center"><span class="badge bg-label-success">Good</span></td>
                <td>
                    ${comments}
                    <input type="hidden" name="reports[${index}][comments]" value="${comments}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-label-danger btn-xs" onclick="removeRow(${index})">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#report_list_body').append(html);
        reportLines.push({ project: project.val() }); // Just for indexing
        
        // Reset entry form
        $('#entry_records, #entry_hours, #entry_comments').val('');
        $('#entry_project, #entry_task').val('').trigger('change');
    }

    $('#work-from-home-attendance-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        submitBtn.prop('disabled', true).text('Submitting...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Reset form
                        form[0].reset();
                        $('#report_list_body').find('tr:not(#break_time_row)').remove();
                        reportLines = [];
                        
                        // Notify parent to refresh UI
                        if (typeof refreshAttendanceStatus === 'function') {
                            refreshAttendanceStatus();
                        } else {
                            setTimeout(() => { window.location.reload(); }, 1000);
                        }
                    // Update balance time if returned
                    if (response.balance_time) {
                        $('#balance_time_display').text(response.balance_time);
                    }
                } else {
                    toastr.error(response.message || 'Something went wrong');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    let errorMessages = Object.values(errors).flat().join('<br>');
                    toastr.error(errorMessages);
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    function removeRow(index) {
        $(`#report_row_${index}`).remove();
    }
</script>
@endpush