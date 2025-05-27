<form action="{{ route('others.events.store') }}" method="post" id="event-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        {{-- Attendance Section --}}
        <div class="col-sm-12 mb-4">
            <h5>Attendance Details</h5>
            <div class="row">
                <div class="form-group mb-2 col-sm-12">
                    <label>Username</label>
                    <input type="text" name="attendance[username]" class="form-control" required>
                </div>
                <div class="form-group mb-2 col-sm-6">
                    <label>Sign-in Date</label>
                    <input type="date" name="attendance[signin_date]" class="form-control">
                </div>

                <div class="form-group mb-2 col-sm-6">
                    <label>Sign-out Date</label>
                    <input type="date" name="attendance[signout_date]" class="form-control">
                </div>

                <div class="form-group mb-2 col-sm-6">
                    <label>Sign-in Time</label>
                    <input type="time" name="attendance[signin_time]" class="form-control">
                </div>

                <div class="form-group mb-2 col-sm-6">
                    <label>Sign-out Time</label>
                    <input type="time" name="attendance[signout_time]" class="form-control">
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
                    <tbody id="report-lines">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-sm btn-success my-2" onclick="addReportLine()">+ Add Report Line</button>

            <div class="form-group mt-2">
                <label for="excelUpload">Or Upload Excel File</label>
                <input type="file" name="excel_file" id="excelUpload" class="form-control-file" accept=".xlsx, .xls" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</form>
@push('js')

<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
<script>
function addReportLine(data = {}) {
    const container = document.getElementById('report-lines');
    const wrapper = document.createElement('tbody');
    wrapper.innerHTML = `
        <tr>
            <td>
                <input type="text" name="reports[][project_name]" class="form-control" placeholder="Project Name" value="${data.project_name || ''}" />
            </td>
            <td>
                <input type="text" name="reports[][type_of_work]" class="form-control" placeholder="Type of Work" value="${data.type_of_work || ''}" />
            </td>
            <td>
                <input type="text" name="reports[][time_of_work]" class="form-control" placeholder="Time of Work" value="${data.time_of_work || ''}" />
            </td>
            <td>
                <input type="text" name="reports[][total_time]" class="form-control" placeholder="Total Time" value="${data.total_time || ''}" />
            </td>
            <td>
                <input type="text" name="reports[][comments]" class="form-control" placeholder="Comments" value="${data.comments || ''}" />
            </td>
            <td>
                <input type="text" name="reports[][total_records]" class="form-control" placeholder="Total Records" value="${data.total_records || ''}" />
            </td>
            <td>
                <input type="text" name="reports[][productivity_hour]" class="form-control" placeholder="Productivity Hour" value="${data.productivity_hour || ''}" />
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.closest('.border').remove()">Remove</button>
            </td>
        </tr>
    `;
    container.appendChild(wrapper);
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
</script>

@endpush