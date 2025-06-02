<div class="row" style="height: 500px !important;">
    <form action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'get' : 'post' }}" id="project-task-form" >
        @csrf
        @if(strtolower($method) !== 'post')
            @method($method)
        @endif
    
        <input type="hidden" name="id" id="target_id">
    
        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <div class="d-flex align-items-center justify-content-between">
                        <label for="task_name">Task Name <span class="text-danger">*</span></label>
                        <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Task" onclick="addTask()"><i class="fa fa-plus"></i> Add</a>
                    </div>
                    <!-- <input type="text" name="task_name" id="task_name" class="form-control" placeholder="Task Name" /> -->
                    <select class="form-control select2" name="task_name" id="task_name" data-placeholder="Select Project" required>
                        <option value=""></option>
                        @if($tasks->isNotEmpty()) 
                            @foreach($tasks as $task) 
                                <option value="{{ $task->id }}">{{ $task->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
    
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="project_id">Projects <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="project_id" id="project_id" data-placeholder="Select Project" onchange="getMembers(this.value)" required>
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
    
            <div class="col-sm-12 mb-3">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
            </div>   
        </div>
    </form>

   
</div>

<div class="modal fade" id="popUpModel" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            <div class="modal-body">
                <div class="row">
                    <form action="{{ route('tasks-project.store-task-name') }}" method="post" id="add-task-form">
                        @csrf
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="name">Task Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Task Name" />
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="saveFormDate()"  class="btn btn-primary waves-effect waves-light">Save</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function addTask() {
            const modalEl = document.getElementById('popUpModel');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        function saveFormDate() {
            var form = $("#add-task-form");
            var form_data = form.serialize();

            $.ajax({
                type: "post",
                url: form.attr('action'),
                data: form_data,
                dataType: "json",
                success: function (response) {
                    if(response.success) {
                        toastr["success"]("Task saved successfully!");
                        $('#task_name')
                            .append('<option value="'+response.data.id+'" selected>'+response.data.name+'</option>')
                            .trigger('change'); // corrected here
                        const modalElc = document.getElementById('popUpModel');
                        const modalInstance = bootstrap.Modal.getInstance(modalElc);
                        if (modalInstance) {
                            modalInstance.hide();  // closes the modal
                        } else {
                            // fallback: create instance and hide
                            new bootstrap.Modal(modalElc).hide();
                        }
                        form[0].reset(); // you can enable this if needed
                    } else {
                        toastr["error"]("Failed to save the task.");
                    }
                },
                error: function(xhr) {
                    toastr["error"]("Something went wrong!");
                }
            });
        }

    </script>
@endpush