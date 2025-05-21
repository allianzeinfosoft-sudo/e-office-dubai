@extends('layouts.app')

@section('css')

@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">    
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Project /</span> {{ $meta_title }}</h4>
                    
                    <div class="row">
                    <div class="col-lg-12 mb-4" id="workReportFormContainer">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <small class="d-block mb-1"> You must be enter your work report</small>
                                    </div>
                                    <h4 class="card-title mb-1"> <i class="ti ti-printer ti-sm"></i> {{ $meta_title }}</h4>
                                </div>
                                <div class="card-body">
                                    <form id="workReportForm" action="{{ route('work-report.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="work_report_id" id="work_report_id">
                                        <input type="hidden" name="_method" id="formMethod" value="POST">

                                        <div class="row">
                                            <div class="col-sm-6 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="emp_id" class="form-label">User</label>
                                                    <select name="emp_id" data-placeholder="Select Type of Work" id="emp_id" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                        @if($employees->isNotEmpty())
                                                            @foreach($employees as $employee)
                                                            <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="report_date" class="form-label">Report Date</label>
                                                    <input type="text" name="report_date" id="report_date" placeholder="Report Date" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="project_name" class="form-label">Project</label>
                                                    <select name="project_name" id="project_name" data-placeholder="Select Project" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                        @if($projects->isNotEmpty())
                                                            @foreach($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>   

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="type_of_work" class="form-label">Type of Work</label>
                                                    <select name="type_of_work" data-placeholder="Select Type of Work" id="type_of_work" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="total_records" class="form-label">Total Records / Tasks</label>
                                                    <input type="text" name="total_records" id="total_records" placeholder="Totla Records / Tasks" class="form-control" />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="productivity_hour" class="form-label">Productivity Per Hour</label>
                                                    <input type="text" name="productivity_hour" id="productivity_hour" placeholder="Productivity per hour" class="form-control" />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="total_time" class="form-label">No. of Hours</label>
                                                    <input type="text" name="total_time" id="total_time" placeholder="No. of Hours" step="2" value="" class="form-control" required />
                                                </div>
                                            </div>    

                                            <div class="col-sm-12 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="comments" class="form-label">Comments</label>
                                                    <textarea name="comments" id="comments" class="form-control" rows="5"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12 mb-2 g-2 d-flex justify-content-end">
                                                <button type="button" id="submitForm" class="btn btn-primary"><i class="ti ti-plus"></i> Save</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <x-footer /> 
                <!-- / Footer -->
                 
                <div class="content-backdrop fade"></div>

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>

                <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>
@stop


@push('js')
<script>
    $(function(){

        $('#report_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        $('#total_time').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i:S',
            time_24hr: true,
            enableSeconds: true
        });

        $('#project_name').on('change', function () {
            let project_id = $(this).val();
            if (project_id) {
                let url = `{{ route('tasks-project.get-tasks-by-project', ':project_id') }}`.replace(':project_id', project_id);

                $.ajax({
                    type: "GET",
                    url: url, // ✅ Removed incorrect semicolon
                    success: function (response) {
                        if (response.success) {
                            let options = '<option value="">Select a task</option>';
                            response.data.forEach(task => {
                                options += `<option value="${task.tasks.id}">${task.tasks.name}</option>`;
                            });
                            $('#type_of_work').html(options);
                        }else{
                            $('#type_of_work').html('<option value="">Select a task</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#submitForm').on('click', function() {
            var formData = new FormData($('#workReportForm')[0]);

            $.ajax({
                url: "{{ route('work-report.custom-workstore') }}", 
                type: 'POST',
                data: formData,
                contentType: false, // This tells jQuery not to process the data
                processData: false, // This tells jQuery not to set contentType
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Success message or reload
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload page or reset form as needed
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Something went wrong. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value + '\\n';
                        });
                        alert(errorMsg);
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                }
            });
        });

        $('#type_of_work').on('change', function() {
            let task_id = $(this).val();
            let project_id = $('#project_name').val();

            if (task_id) {
                let url = `{{ route('work-report.get-productivity-target') }}`;

                $.ajax({
                    type: "post",
                    url: url, 
                    data :{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        task_id: task_id,
                        project_id: project_id
                    },
                    success: function (response) {
                        if(response.success){
                            $('#productivity_hour').val((response.data.rph) ? response.data.rph : 0);
                        }else{
                            $('#productivity_hour').val('0');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
        
    });
</script>
@endpush