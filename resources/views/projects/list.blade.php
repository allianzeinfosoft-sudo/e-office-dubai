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
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                <div class="card">
                    <div class="card-datatable table-responsive">
                        <div class=" float-end mt-15 mr-20">
                            <a href="{{ route('project.create') }}">
                                <button class="btn btn-secondary add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button">
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add New</span>
                                    </span>
                                </button>
                            </a>
                        </div>

                        <table class="datatables-basic datatables-projects table border-top">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Department</th>
                                    <th>Add By</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>  
                </div>
            </div>
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
                    { data: 'project_name', title: 'Project Name' },
                    { data: 'department_id', title: 'Department ID' },
                    { data: 'project_add_person', title: 'Added By' },
                    { data: 'start_date', title: 'Start Date' },
                    { data: 'end_date', title: 'End Date' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary edit-project" data-id="${row.id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-project" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ]
            });
        }

    })
</script>

@stop