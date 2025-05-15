@extends('layouts.app')

@section('css')

<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Project /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="addProject()">
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
                        <table class="datatables-basic datatables-projects-list table border-top table-stripedc table-hover table-striped"></table>
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

<!-- Add Project From -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="add_projects_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i> 
            <span class="">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Create Project</h5>
                <span class="text-white slogan">Create New Project</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-project-form action="{{ route('project.store') }}" />
            </div>
        </div>
    </div>
</div>

<!-- Edit Project From -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="edit_projects_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i> 
            <span class="">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Project</h5>
                <span class="text-white slogan">Edit New Project</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-project-form id="edit-project-form" action="" method="POST" />
            </div>
        </div>
    </div>
</div>
@stop


@push('js')
<script>
    
    $(function(){
        var projectTable = $('.datatables-projects-list');

        if (projectTable.length) {

            // Destroy existing DataTable instance if already initialized
            if ($.fn.DataTable.isDataTable(projectTable)) {
                projectTable.DataTable().clear().destroy();
            }

            projectTable.DataTable({
                processing: true,
                serverSide: false, 
                ajax: {
                    type: "GET",
                    url: "{{ route('projects.get-projects') }}", 
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'project_name', title: 'Project Name' },
                    { data: 'department_name', title: 'Department' },
                    { data: 'user_name', title: 'Added By' },
                    { data: 'start_date', title: 'Start Date' },
                    { data: 'end_date', title: 'End Date' },
                    {
                        data: 'id',
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" onclick="editProject(${data})" class="btn btn-sm btn-icon btn-primary edit-project">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger delete-project" onclick="deleteProject(${data})" data-id="${data}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });
        }
    });

    function deleteProject(projectId) {
        if (confirm('Are you sure you want to delete this project?')) {
            $.ajax({
                url: "{{ route('projects.destroy', ':id') }}".replace(':id', projectId), // ✅ Correct route name
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    $('.datatables-projects-list').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }

    function addProject() {
        var offcanvasElement = $('#add_projects_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

    function editProject(projectId) {
        var offcanvasElement = $('#edit_projects_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();

        var editUrl = "{{ route('project.edit', ':id') }}".replace(':id', projectId);
        $.ajax({
            type: "get",
            url: editUrl,
            dataType: "json",
            success: function (response) {
                let updateUrl = "{{ route('project.update', ':project') }}".replace(':project', response.project.id);
                offcanvasElement.find('input[name="project_name"]').val(response.project.project_name);
                offcanvasElement.find('select[name="project_add_person"]').val(response.project.project_add_person).trigger('change');
                offcanvasElement.find('select[name="department_id"]').val(response.project.department_id).trigger('change');
                offcanvasElement.find('input[name="start_date"]').val(response.project.start_date);
                offcanvasElement.find('#start_date').flatpickr({ 
                    monthSelectorType: 'static',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    dateFormat: 'd-m-Y',
                    defaultDate : response.project.start_date
                });

                offcanvasElement.find('#end_date').flatpickr({ 
                    monthSelectorType: 'static',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    dateFormat: 'd-m-Y',
                    defaultDate : response.project.start_date
                });
                offcanvasElement.find('input[name="total_hours"]').val(response.project.total_hours);
                offcanvasElement.find('input[name="total_day"]').val(response.project.total_day);
                offcanvasElement.find('#project-form').attr('action', updateUrl);
            }
        });
    }

</script>
@endpush