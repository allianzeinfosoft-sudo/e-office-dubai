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
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>
                    @can('custom attendance approval')
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <!-- {{ route('project.create') }} -->
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    @endcan

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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="project_tasks_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Create Project Task</h5>
                <span class="text-white slogan">Create New Project Tasks</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12 h-100">
                <x-project-task-form action="{{ route('tasks-project.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@section('js')
<script>

     window.userPermissions = {
        view: @json(auth()->user()->can('view tasks-project')),
        create: @json(auth()->user()->can('create tasks-project')),
        edit: @json(auth()->user()->can('edit tasks-project')),
    };

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
                                <img class="rounded-circle" src="${row.reporting_to && row.reporting_to.profile_image  ? '/storage/profile_pics/' + row.reporting_to.profile_image.replace(/^profile_pics\//, '')  : '../../assets/img/avatars/default-avatar.png'}" alt="`+ row.reporting_to.full_name +`" title="`+ row.reporting_to.full_name +`">
                            </li>
                          </ul>
                        </div>`;
                        }
                    },
                    { data: 'members', title: 'Members',
                        render: function (data, type, row) {
                            if (!Array.isArray(data) || data.length === 0) {
                                return `<span>No Members</span>`;
                            }

                            let membersHtml = `<div class="d-flex align-items-center">
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">`;

                            data.forEach(member => {
                                membersHtml += `<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"  class="avatar avatar-sm pull-up" aria-label="${member.full_name}"  data-bs-original-title="${member.full_name}">
                                                    <img class="rounded-circle" src="${member.profile_image ? '/storage/profile_pics/' + member.profile_image.replace(/^profile_pics\//, '') : '../../assets/img/avatars/default-avatar.png' }" alt="Avatar" title="${member.full_name}">
                                                </li>`;
                                });

                            membersHtml += `</ul></div>`;

                            return membersHtml;
                        }
                     },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            const editUrl = "{{ route('tasks-project.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-project" onclick="openOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
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

    function getMembers(value) {
        const url = `/tasks-project/${value}/get-members`;
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function(response) {
                const $membersSelect = $('#members');
                $membersSelect.empty();
                if (response.success && Array.isArray(response.data)) {
                    let options = "<option value=''></option>";
                    response.data.forEach(member => {
                        options += `<option value="${member.user_id}">${member.full_name} (${member.employeeID})</option>`;
                    });
                    $membersSelect.html(options);
                    $membersSelect.select2({
                        dropdownParent: $('#project-task-form'),
                        placeholder: "Select an option",
                        allowClear: true
                    });
                } else {
                    console.error('Invalid response data:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }

function openOffcanvas(targetId = null) {
    $('#project-task-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Project Task</h5><span class="text-white slogan">Edit New Project Tasks</span>`);
        $.ajax({
            url: `/tasks-project/${targetId}/edit`,
            type: 'GET',
            success: function (data) {
                $('#target_id').val(data.projectTask.id).trigger('change');
                $('#task_name').val(data.projectTask.task_name).trigger('change');
                $('#project_id').val(data.projectTask.project_id).trigger('change');
                $('#reporting_to').val(data.projectTask.reporting_to).trigger('change');
                setTimeout(() => {
                    $('#members').val(data.projectTask.members).trigger('change');
                }, 500);
            }
        });
    }
    var offcanvasElement = $('#project_tasks_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

</script>
@stop
