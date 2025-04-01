<form action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'get' : 'post' }}" id="{{ $form_id ?? 'project-task-form' }}" >
    @csrf
    @if(strtolower($method) !== 'post')
        @method($method)
    @endif
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input type="text" name="task_name" id="task_name" class="form-control" placeholder="Task Name" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="project_id">Projects</label>
                <select class="form-control select2" name="project_id" id="project_id" data-placeholder="Select Project">
                    <option value=""></option>
                    @if($projects->isNotEmpty()) 
                        @foreach($projects as $project) 
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="reporting_to">Reporting To</label>
                <select class="form-control select2" name="reporting_to" id="reporting_to" data-placeholder="Select Reporting To" onchange="getMembers(this.value)">
                    <option value=""></option>
                    @if($reportingTo->isNotEmpty()) 
                        @foreach($reportingTo as $user) 
                            <option value="{{ $user->id }}">{{ $user->full_name }} ( {{ $user->employeeID }} )</option>
                        @endforeach
                    @endif
                 </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group" id="membersContainer">
                <label for="members">Members</label>
                <select class="form-control" name="members[]" id="members" data-placeholder="Select Members" multiple="multiple">
                    <option value=""></option>
                 </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>   
    </div>
</form>