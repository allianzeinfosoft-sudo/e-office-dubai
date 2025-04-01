<form id="{{ $id ?? 'project-form' }}"  action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'get' : 'post' }}">
    @csrf
    @if(strtolower($method) !== 'post')
        @method($method)
    @endif

    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="project_name">Project Name</label>
                <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Project Name"
                    value="{{ old('project_name', $project->project_name ?? '') }}" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="project_add_person">Add By</label>
                <select class="form-control select2" name="project_add_person" id="project_add_person" data-placeholder="Select Project">
                    <option value=""></option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('project_add_person', $project->project_add_person ?? '') == $user->id ? 'selected' : '' }} >
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="department_id">Department</label>
                <select class="form-control select2" name="department_id" id="department_id" data-placeholder="Select Department">
                    <option value=""></option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $project->department_id ?? '') == $department->id ? 'selected' : '' }} >
                            {{ $department->department }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control flatpickr-input" placeholder="Start Date" value="{{ old('start_date', $project->start_date ?? '') }}" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control flatpickr-input" placeholder="End Date" value="{{ old('end_date', $project->end_date ?? '') }}" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="total_hours">Total Hours</label>
                <input type="text" name="total_hours" id="total_hours" class="form-control" placeholder="Total Hours"
                    value="{{ old('total_hours', $project->total_hours ?? '') }}" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="total_day">Total days</label>
                <input type="text" name="total_day" id="total_day" class="form-control" placeholder="Total Days"
                    value="{{ old('total_day', $project->total_day ?? '') }}" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;&nbsp; Save</button>
        </div>
    </div>
</form>
