@extends('layouts.app')

@section('css')

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

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div class=" float-end mt-15 mr-20">
                                <a href="{{ route('tasks-project.create') }}">
                                    <button class="btn btn-secondary add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button">
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </a>
                            </div>
    
                            <table class="datatables-basic datatables-project-tasks table border-top table-stripedc">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Project</th>
                                        <th>Date</th>
                                        <th>Pr Task</th>
                                        <th>Pr. Sub. Task</th>
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
                    { data: 'pr_task_id', title: 'Pr Task' },
                    { data: 'pr_sub_task_id', title: 'Pr. Sub. Task' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            const editUrl = "{{ route('tasks-project.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-primary edit-project">Edit</a>
                                <button type="button" class="btn btn-sm btn-danger delete-project" onclick="deleteProjectTask(${row.id})" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ]
            });
        }
    });

    function deleteProjectTask(projectTask) {
        if (confirm('Are you sure you want to delete this project?')) {
            $.ajax({
                url: "{{ route('tasks-project.destroy', ':projectTask') }}".replace(':projectTask', projectTask), // ✅ Correct route name
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    $('.datatables-project-tasks').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }
    
</script>
@stop