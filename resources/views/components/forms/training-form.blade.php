@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('trainings.store') }}" method="POST" id="trainings-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="trainings_title">Training Title <span class="text-danger">*</span></label>
                <input type="text" name="trainings_title" id="trainings_title" class="form-control" placeholder="Training Title" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="template">Department <span class="text-danger">*</span></label>
                <select name="department" id="department" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select departments">
                    <option value=""></option>
                    <option value="0">All</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ auth()->user()->employee?->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->department ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                    <label for="employee">Employee <span class="text-danger">*</span></label>
                    <select name="employee[]" id="employee" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select employee" multiple="multiple" >
                        <option value=""></option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->user_id }}"> {{ $employee->full_name  ?? '' }} </option>
                        @endforeach
                    </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="start_date_time">Start Date Time <span class="text-danger">*</span></label>
                <input type="datetime-local" name="start_date_time" id="start_date_time" class="form-control" placeholder="Start date time" required>

            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="end_date_time">End Date Time <span class="text-danger">*</span></label>
                <input type="datetime-local" name="end_date_time" id="end_date_time" class="form-control" placeholder="End date time" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="trainings_details">Trainings Details</label>
                <div id="trainings-editor"></div>
                <input type="hidden" name="trainings_details" value="{{ strip_tags(old('trainings_details')) }}" id="trainings_details">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="trainings_details">Document</label>
                <div class="mb-3">
                    <div class="input-group input-group-merge">
                        <input class="form-control" type="file" id="document" name="document" />
                    </div>
                </div>
            </div>
        </div>


        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>
@push('js')
    <script>

    // form validation
    $(document).ready(function() {
        $('#department').on('change', function() {
            var departmentId = $(this).val();
            if (departmentId) {
                $.ajax({
                    url: `/employees/${departmentId}`,
                    type: 'GET',
                    success: function(response) {

                        $('#employee').empty().append('<option value="">Select</option>');
                            if (response.employees.length > 0) {

                                let $employeeSelect = $('#employee');
                                $employeeSelect.empty();
                                let optionsHtml = '<option value="0">All</option>';
                                $.each(response.employees, function(index, employee) {
                                    optionsHtml += '<option value="' + employee.user_id + '">' + employee.full_name + '</option>';
                                });
                                $employeeSelect.append(optionsHtml);

                            }


                    },
                    error: function(xhr) {
                        console.error('Error fetching survey:', xhr.responseText);
                    }
                });
            } else {
                $('#template').empty().append('<option value="">Select</option>');
            }
        });

        $('#department').trigger('change');
    });
    </script>
@endpush
