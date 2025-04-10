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
                <h4 class="fw-bold py-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

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

                <div class="card">
                    <div class="card-datatable table-responsive">
                        <div class=" float-end mt-15 mr-20">
                        </div>

                        <table class="datatables-basic datatables-projects table border-top table-stripedc table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Job Title</th>
                                    <th>Designation</th>
                                    <th>Project Name</th>
                                    <th>Priority</th>
                                    <th>Interviewer</th>
                                    <th>Status</th>
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

<!-- Add Project From -->
<div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="rrf_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i> 
            <span class="">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel">Create Recruitment </h5>
                <span class="text-white slogan">Create New Recruiments</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-recuitment-form action="#" />
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>

    $(function() {
        
        var projectTable = $('.datatables-projects'),
            select2 = $('.select2');

        if (projectTable.length) {            
            projectTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('projects.index') }}", // Fixed syntax
                    dataType: "json", 
                    dataSrc: "data"  
                },
                columns: [
                    { data: 'project_name', title: 'No.' },
                    { data: 'department_name', title: 'Date' },
                    { data: 'user_name', title: 'Job Title' },
                    { data: 'user_name', title: 'Designation' },
                    { data: 'start_date', title: 'Project Name' },
                    { data: 'end_date', title: 'Priority' },
                    { data: 'end_date', title: 'Interviewer' },
                    { data: 'end_date', title: 'Status' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            const editUrl = "{{ route('project.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" onclick="editProject(${row.id})" class="btn btn-sm btn-icon btn-primary edit-project"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm  btn-icon btn-danger delete-project" onclick="deleteProject(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        }
       
        $('#start_date,  #end_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

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
                    $('.datatables-projects').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }

    function openOffcanvas(targetId = null) {
        var offcanvasElement = $('#rrf_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

    
    
</script>

@stop