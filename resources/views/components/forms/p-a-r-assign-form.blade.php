@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('store.par.assign') }}" method="POST" id="par-assign-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">
    <div class="row">
        @if(auth()->user()->hasAnyRole(['HR','Developer','G1','G2']))
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="template">Department <span class="text-danger">*</span></label>
                    <select name="department" id="department" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select departments">
                        <option value=""></option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ auth()->user()->employee?->department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->department ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="template">Template <span class="text-danger">*</span></label>
                <select name="template" id="template" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select template">
                    <option value=""></option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->template_name ?? '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="employee">Employee <span class="text-danger">*</span></label>
                <select name="employee[]" id="employee" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select employee" multiple="multiple">
                    <option value=""></option>
                    @foreach ($employees as $employee)
                      <option value="{{ $employee->user_id }}"> {{ $employee->full_name  ?? '' }} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="par_start_date">PAR Start Date <span class="text-danger">*</span></label>
                <input type="date" name="par_start_date" id="par_start_date" class="form-control" placeholder="PAR start date" require />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="par_end_date">PAR End Date <span class="text-danger">*</span></label>
                <input type="date" name="par_end_date" id="par_end_date" class="form-control" placeholder="PAR end date" require />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>
@push('js')
<script>
$(document).ready(function() {

      $('#department').on('change', function() {
          var departmentId = $(this).val();
          if (departmentId) {
              $.ajax({
                url: `/branches/${departmentId}/templates`,
                  type: 'GET',
                  success: function(response) {

                      $('#template').empty().append('<option value="">Select</option>');

                      if (response.templates.length > 0) {

                          $.each(response.templates, function(index, template) {
                              $('#template').append('<option value="' + template.id + '">' + template.template_name + '</option>');
                          });
                      } else {
                          $('#template').append('<option value="">No templates found</option>');
                      }

                    $('#employee').empty().append('<option value="">Select</option>');
                        if (response.employees.length > 0) {
                            $.each(response.employees, function(index, employee) {
                                $('#employee').append('<option value="' + employee.user_id + '">' + employee.full_name + '</option>');
                            });
                        }


                  },
                  error: function(xhr) {
                      console.error('Error fetching template:', xhr.responseText);
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
