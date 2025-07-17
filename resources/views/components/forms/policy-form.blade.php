<form action="{{ route('others.policies.store') }}" method="post" id="policy-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="policyTitle">Policy Title <span class="text-danger">*</span></label>
                <input type="text" name="policyTitle" id="policyTitle" class="form-control" placeholder="Policy Title" required />
            </div>
        </div>

        {{-- <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="policyStartDate">Policy Start Date <span class="text-danger">*</span></label>
                <input type="text" name="policyStartDate" id="policyStartDate" class="form-control flatpickr-input" placeholder="Policy Start Date" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="pollicyEndDate">Policy End Date</label>
                <input type="text" name="pollicyEndDate" id="pollicyEndDate" class="form-control flatpickr-input" placeholder="Policy End Date" />
            </div>
        </div> --}}

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="department_id">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="form-control select2" required>
                    <option value="">Select Department</option>
                    <option value="0">General</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" class="form-control select2">

                </select>
            </div>
        </div>


        {{-- <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="role_id">Group </label>
                <select name="role_id" id="role_id" class="form-control select2" >
                    <option value="">Select Group</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="description-editor">Policy Description</label>
                <div id="policy-description-editor"></div>
                <input type="hidden" name="descriptions" id="description">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="attachments">Attachments <span class="text-danger">*</span></label>
                <input type="file" name="attachments" id="attachments" class="form-control" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save
            </button>
        </div>
    </div>
</form>

@push('js')
<script>
   $(document).ready(function() {
        $('#department_id').change(function() {
            var departmentId = $(this).val();

            $('#project_id').html('<option value="">Loading...</option>');

            if (departmentId) {
                $.ajax({
                    url: '{{ route("get.projects.by.department") }}',
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(response) {
                        $('#project_id').empty().append('<option value="">Select Project</option>');
                        $.each(response.projects, function(key, project) {
                            $('#project_id').append('<option value="' + project.id + '">' + project.project_name + '</option>');
                        });
                    },
                    error: function() {
                        $('#project_id').html('<option value="">Select Project</option>');
                    }
                });
            } else {
                $('#project_id').html('<option value="">Select Project</option>');
            }
        });
    });
</script>
@endpush
