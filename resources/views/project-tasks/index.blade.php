@extends('layouts.app')

@section('css')
<style>
.w-35 {
    width: 35% !important;
}
.w-45 {
    width: 45% !important;
}
.offcanvas-close{
    position: absolute;
    top: 0px;
    left: -32px;  /* Moves the button outside the offcanvas */
    z-index: 1055; /* Ensures it stays on top */
    padding: 28px 10px;
    border-radius: 0px;
}
</style>
@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">    
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="addProjectTask()">
                                <!-- {{ route('project.create') }} -->
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-basic datatables-project-tasks table border-top table-stripedc">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Project</th>
                                        <th>Date</th>
                                        <th>Reporting To</th>
                                        <th>Members</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>  
                    </div>

                </div>


                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->
            </div>
        </div>
    </div>
</div>

<!-- Add Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="add_project_tasks_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i> 
            <span class="">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Create Project Task</h5>
                <span class="text-white slogan">Create New Project Tasks</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-project-task-form action="{{ route('tasks-project.store') }}" />
            </div>
        </div>
    </div>
</div>

<!-- Edit Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="edit_project_tasks_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i> 
            <span class="">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Project Task</h5>
                <span class="text-white slogan">Edit New Project Tasks</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-project-task-form action="" method="POST" />
            </div>
        </div>
    </div>
</div>

@stop


@section('js')
<script>
    $(function() {
        var projectTable = $('.datatables-project-tasks'),
            select2 = $('.select2');

        if (projectTable.length) {            
            projectTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('tasks-project.index') }}", // Fixed syntax
                    dataType: "json", 
                    dataSrc: "data"  
                },
                columns: [
                    { data: 'task_name', title: 'Task' },
                    { data: 'project_name', title: 'Project' },
                    { data: 'created_at', title: 'Date' },
                    { data: 'reporting_to', title: 'Reporting To',
                        render: function (data, type, row) {
                            return `<div class="d-flex align-items-center">
                          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kaith D'souza" data-bs-original-title="Kaith D'souza">
                              <img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar" title="username">
                            </li>
                          </ul>
                        </div>`;
                        }
                    },
                    { data: 'members', title: 'Members',
                        render: function (data, type, row) {
                            return `<div class="d-flex align-items-center">
                          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Kaith D'souza" data-bs-original-title="Kaith D'souza">
                              <img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar">
                            </li>
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="John Doe" data-bs-original-title="John Doe">
                              <img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar">
                            </li>
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="Alan Walker" data-bs-original-title="Alan Walker">
                              <img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar">
                            </li>
                          </ul>
                        </div>`;
                        }
                     },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            const editUrl = "{{ route('tasks-project.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-project" onclick="editProjectTask(${row.id})"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger delete-project" onclick="deleteProjectTask(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        }
    });

    function deleteProjectTask(projectTask) {
        if (confirm('Are you sure you want to delete this Task?')) {
            $.ajax({
                url: "{{ route('tasks-project.destroy', ':projectTask') }}".replace(':projectTask', projectTask), // ✅ Correct route name
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    $('.datatables-project-tasks').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                    window.location.reload();
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }

    function addProjectTask() {
        var offcanvasElement = $('#add_project_tasks_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

    function getMembers(value) {
    var url = '/tasks-project/' + value + '/get-members';
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function(response) {
            // Clear existing options
            $('#members').empty();

            if (response.success && Array.isArray(response.data)) {
                // Add a default empty option
                var html = "<option value=''></option>";

                // Iterate over each member and create an option
                $.each(response.data, function(index, member) {
                    html += "<option value='" + member.id + "'>" + member.full_name	 + " (" + member.employeeID + ")</option>";
                });

                // Append the options to the select element
                $('#members').html(html);

                // Reinitialize Select2 if it's being used
                //$('#members').select2();
            } else {
                console.error('Invalid response data:', response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}

function editProjectTask(projectTaskId) {
    var offcanvasElement = $('#edit_project_tasks_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();

    const editUrl = "{{ route('tasks-project.edit', ':id') }}".replace(':id', projectTaskId);
    $.ajax({
        type: "get",
        url: editUrl,
        dataType: "json",
        success: function (response) {
            let updateUrl = "{{ route('tasks-project.update', ':projectTask') }}".replace(':projectTask', response.projectTask.id);
            offcanvasElement.find('input[name="task_name"]').val(response.projectTask.task_name);
            offcanvasElement.find('select[name="project_id"]').val(response.projectTask.project_id).trigger('change');
            offcanvasElement.find('select[name="reporting_to"]').val(response.projectTask.reporting_to).trigger('change');
            setTimeout(() => {
                var url = '/tasks-project/' + response.projectTask.reporting_to + '/get-members';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    success: function(response) {
                        // Clear existing options
                        offcanvasElement.find('#members').empty();

                        if (response.success && Array.isArray(response.data)) {
                            // Add a default empty option
                            var html = "<option value=''></option>";

                            // Iterate over each member and create an option
                            $.each(response.data, function(index, member) {
                                html += "<option value='" + member.id + "'>" + member.full_name	 + " (" + member.employeeID + ")</option>";
                            });

                            // Append the options to the select element
                            offcanvasElement.find('#members').html(html);

                            // Reinitialize Select2 if it's being used
                            //$('#members').select2();
                        } else {
                            console.error('Invalid response data:', response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
                offcanvasElement.find('select[name="members"]').val(response.projectTask.members).trigger('change');
            }, 500);

            offcanvasElement.find('#project-task-form').attr('action', updateUrl);
        }
    });
}
    
</script>
@stop