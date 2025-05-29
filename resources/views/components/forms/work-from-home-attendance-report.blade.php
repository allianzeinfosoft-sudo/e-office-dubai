<form action="{{ route('work-from-home-attendance.store') }}" method="post" id="work-from-home-attendance-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        {{-- Attendance Section --}}
        <div class="col-sm-12 mb-4">
            <h5>Attendance Details</h5>
            <div class="row">
                
                <div class="form-group mb-2 col-sm-6">
                    <label>Employee <span class="text-danger">*</span></label>
                    <select class="form-control select2" data-placeholder="Select Employee" name="employee_id" id="{{ $type }}_emp_id">
                        <option value="">Select Employee</option>
                        @if($employees->count() > 0)
                            @foreach($employees as $employee)
                                <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group mb-2 col-sm-6">
                    <label>Attendance Date <span class="text-danger">*</span></label>
                    <input type="text" name="signin_date" id="{{ $type }}_signin_date" class="form-control">
                </div>

                <div class="form-group mb-2 col-sm-4">
                    <label>Sign-in Time <span class="text-danger">*</span></label>
                    <input type="time" name="signin_time" id="{{ $type }}_signin_time" class="form-control">
                </div>

                <div class="form-group mb-2 col-sm-4">
                    <label>Break Time <span class="text-danger">*</span></label>
                    <input type="time" name="brake_time" class="form-control" id="{{ $type }}_brake_time" value="01:00">
                </div>

                <div class="form-group mb-2 col-sm-4">
                    <label>Sign-out Time <span class="text-danger">*</span></label>
                    <input type="time" name="signout_time" id="{{ $type }}_signout_time" class="form-control">
                </div>
            </div>
        </div>

        {{-- Report Section --}}
        <div class="col-sm-12 mb-4">
            <h5>Work From Home Reports</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Type of Work</th>
                            <th>Records/Tasks</th>
                            <th>productivity</th>
                            <th>No.of hrs</th>
                            <th>Comments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="{{ $type }}_report-lines">
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-sm btn-success my-2" onclick="addReportLine('{{ $type }}')">+ Add Report Line</button>

           <!--  <div class="divider">
                <div class="divider-text">OR</div>
            </div>

            <div class="form-group mt-2 col-sm-6">
                <label for="excelUpload">Upload Excel File</label>
                <input class="form-control" type="file" id="formFile" name="excelUpload" accept=".xlsx, .xls">
            </div> -->
        </div>

        <div class="col-sm-12 mb-3">
            <input type="hidden" name="work_type" id="work_type" value="{{ $type }}">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</form>
@push('js')

<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
<script>
    
function addReportLine(workType, data = {}) {
    let container;

    if (workType === 'wfh') {
        container = document.getElementById('wfh_report-lines');
    } else {
        container = document.getElementById('wfs_report-lines');
    }

    const projects = {!! json_encode($projects) !!};
    const containerLength = container.children.length;

    const wrapper = `
        <tr>
            <td>
                <select class="form-control select2" data-placeholder="Select Project" name="reports[${containerLength}][project_id]" id="project_${containerLength}" onchange="getProjectTasks(this.value, '${containerLength}')" required>
                    <option value=""></option>
                    ${projects.map(project => `<option value="${project.id}">${project.project_name}</option>`).join('')}
                </select>
            </td>
            <td>
                <select class="form-control" id="type_of_work_${containerLength}" data-placeholder="Select Task" name="reports[${containerLength}][type_of_work]" onchange="getProductivity(this.value, '${containerLength}')" required>
                    <option value="">Select Project</option>
                </select>
            </td>
            <td>
                <input type="text" name="reports[${containerLength}][total_records]" class="form-control" placeholder="Total Records" value="${data.total_records || ''}" />
            </td>
            <td>
                <input type="text" name="reports[${containerLength}][productivity_hour]" id="productivity_hour_${containerLength}" class="form-control" placeholder="Productivity Hour" value="${data.productivity_hour || ''}" readonly />
            </td>
            <td>
                <input type="text" name="reports[${containerLength}][total_time]" class="form-control" placeholder="Total Time" value="${data.total_time || ''}" />
            </td>
            <td>
                <input type="text" name="reports[${containerLength}][comments]" class="form-control" placeholder="Comments" value="${data.comments || ''}" />
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.closest('tr').remove()">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        </tr>
    `;

    container.insertAdjacentHTML('beforeend', wrapper);

    // Reinitialize select2 for dynamically added elements
    //$('.select2').select2();
}

document.getElementById('excelUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(evt) {
        const data = evt.target.result;
        const workbook = XLSX.read(data, { type: 'binary' });
        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
        const rows = XLSX.utils.sheet_to_json(firstSheet, { defval: '' });

        rows.forEach(row => {
            addReportLine({
                username: row.username,
                emp_id: row.emp_id,
                project_name: row.project_name,
                type_of_work: row.type_of_work,
                time_of_work: row.time_of_work,
                total_time: row.total_time,
                comments: row.comments,
                report_date: row.report_date,
                total_records: row.total_records,
                productivity_hour: row.productivity_hour,
            });
        });
    };
    reader.readAsBinaryString(file);
});

function getProjectTasks(projectId, index) {
    let url = `{{ route('tasks-project.get-tasks-by-project', ':id') }}`.replace(':id', projectId);
    $.ajax({
        type: "get",
        url: url,
        dataType: "json",
        success: function (response) {
            $('#type_of_work_'+index).empty();
            var html = '<option value=""></option>';
            response.data.forEach(projectTask => {
                html += '<option value="'+projectTask.tasks.id+'">'+projectTask.tasks.name+'</option>';
            });
            $('#type_of_work_'+index).html(html);
        }
    });
}

function getProductivity(taskId, index) {
    let url = `{{ route('work-report.get-productivity-target', ':id') }}`.replace(':id', taskId);
     let task_id = taskId;
     let project_id = $('#project_'+index).val();
    $.ajax({
        type: "post",
        url: url,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            task_id: task_id,
            project_id: project_id
        },
        dataType: "json",
        success: function (response) {
            if(response.success){
                $('#productivity_hour_'+index).val(response.data.rph);
            }else{
                $('#productivity_hour_'+index).val('0');
            }
        }
    });
}
</script>

@endpush