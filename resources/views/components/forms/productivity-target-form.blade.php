<form id="productivityTargetForm" action="{{ isset($productivityTarget) ? route('productivity-target.update', $productivityTarget->id) : route('productivity-target.store') }}"  method="POST">
    @csrf

    @if(isset($productivityTarget))
        @method('PUT')
    @endif

    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="project_id">Project Name</label>
                <select class="form-control select2" name="project_id" id="project_id" data-placeholder="Select Project">
                    <option value=""></option>
                    @if($projects->isNotEmpty())
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" 
                                {{ isset($productivityTarget) && $productivityTarget->project_id == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="project_task_id">Project Tasks</label>
                <select class="form-control select2" name="project_task_id" id="project_task_id" data-placeholder="Select Project">
                    <option value=""></option>
                    @if(isset($productivityTarget))
                        <option value="{{ $productivityTarget->project_task_id }}" selected>{{ $productivityTarget->task_name }}</option>
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="assignedBy">Assigned By</label>
                <select class="form-control select2" name="assignedBy" id="assignedBy" data-placeholder="Select Department">
                    <option value=""></option>
                    @if($employees->isNotEmpty())
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" 
                                {{ isset($productivityTarget) && $productivityTarget->assignedBy == $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="rph">Total Record Per Hour</label>
                <input type="text" name="rph" id="rph" class="form-control" placeholder="Total Record Per Hour" 
                       value="{{ isset($productivityTarget) ? $productivityTarget->rph : '' }}" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="target_year">Target Month & Year</label>
                <input type="month" name="target_year" id="target_year" class="form-control flatpickr-input" placeholder="Target Year" 
                       value="{{ isset($productivityTarget) ? date('Y-m', strtotime($productivityTarget->target_year)) : date('Y-m') }}" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;&nbsp; Save</button>
        </div>
    </div>
</form>

@push('js')
<script>
    $(function(){
        /* get project tasks */
        $('#project_id').on('change', function(){
            var project_id = $(this).val();
            var url = "{{ route('tasks-project.get-tasks-by-project', ':project_id') }}".replace(':project_id', project_id);
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                success: function (response) {
                    $('#project_task_id').empty();
                    var html = '<option value=""></option>';
                    response.data.forEach(projectTask => {
                        html += '<option value="'+projectTask.id+'">'+projectTask.task_name+'</option>';
                    });
                    $('#project_task_id').html(html);
                }
            });
        });

        /* get reporting person */
        $('#project_task_id').on('change', function(){
            var project_task_id = $(this).val();
            var url = "{{ route('tasks-project.edit', ':projectTask') }}".replace(':projectTask', project_task_id);
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                success: function (response) {
                    $('#assignedBy').val(response.projectTask.reporting_to).trigger('change');
                }
            });
        })
    });
</script>
@endpush
