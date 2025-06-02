<form id="{{ $id ?? 'project-form' }}"  action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'get' : 'post' }}">
    @csrf
    @if(strtolower($method) !== 'post')
        @method($method)
    @endif

    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="project_name">Project Name <span class="text-danger">*</span></label>
                <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Project Name"
                    value="{{ old('project_name', $project->project_name ?? '') }}" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <div class="d-flex align-items-center justify-content-between">
                    <label for="task_name">Task Name <span class="text-danger">*</span></label>
                    <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Task" onclick="addTask()"><i class="fa fa-plus"></i> Add</a>
                </div>
                <!-- <input type="text" name="task_name" id="task_name" class="form-control" placeholder="Task Name" /> -->
                <select class="form-control select2" name="task_name[]" id="task_name" data-placeholder="Select Project" multiple required>
                    <option value=""></option>
                    @if($tasks->isNotEmpty()) 
                        @foreach($tasks as $task) 
                            <option value="{{ $task->id }}">{{ $task->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="project_add_person">Add By <span class="text-danger">*</span></label>
                <select class="form-control select2" name="project_add_person" id="project_add_person" data-placeholder="Select Project" required>
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
                <label for="department_id">Department <span class="text-danger">*</span></label>
                <select class="form-control select2" name="department_id" id="department_id" data-placeholder="Select Department" onchange="getMembers(this.value)" required >
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
                <label for="reporting_to">Reporting To <span class="text-danger">*</span></label>
                <select class="form-control select2" name="reporting_to" id="reporting_to" data-placeholder="Select Reporting To" required>
                    <option value=""></option>
                    @if($reportingTo->isNotEmpty()) 
                        @foreach($reportingTo as $user) 
                            <option value="{{ $user->user_id }}">{{ $user->full_name }} ( {{ $user->employeeID }} )</option>
                        @endforeach
                    @endif
                    </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group" id="membersContainer">
                <label for="members">Members</label>
                <select class="form-control select2" name="members[]" id="members" data-placeholder="Select Members" multiple="multiple">
                    <option value=""></option>
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
