@extends('layouts.app')

@section('css')
<style>
  .dt-buttons{
        float: left;
        margin-top: 10px;
        margin-left: 10px;
    }
    .dataTables_length{
        float: left;
        margin-left: 20px;
    }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container bg-eoffice">
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> Allocated Items Report</h4>
                    <div class="row">
                        <div class="md-4 mb-2">
                        <a class="btn btn-primary" href="{{route('assets.dashboard'); }}">Assets Dashboad</a>
                        </div>
                    </div>
                    <div class="row">

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">

                                    {{-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="classification">Classification</label>
                                            <select name="classification" id="classification" class="form-control select2">
                                                <option value="">All</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">{{ $classification->name ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="asset_item">Asset</label>
                                            <select name="asset_item" id="asset_item" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($master_items as $master_item)
                                                    <option value="{{ $master_item->id }}">{{ $master_item->name ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <select name="department" id="department" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->department ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                   <!-- User Type -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="user_type">User Type</label>
                                            <select name="user_type" id="user_type" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach (config('optionsData.asset_allocation_users') as $key => $value)
                                                    <option value="{{ $key }}"> {{ $value }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Employee Select -->
                                    <div class="col-md-3" id="employee_box" style="display: none;">
                                        <div class="form-group">
                                            <label for="employee">Employees</label>
                                            <select name="employee" id="employee" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->user_id }}">{{ $employee->full_name ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Location Select -->
                                    <div class="col-md-3" id="location_box" style="display: none;">
                                        <div class="form-group">
                                            <label for="location">Locations</label>
                                            <select name="location" id="location" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                     <div class="col-md-3">
                                        <div class="form-group">
                                           <label for="project">Projects</label>
                                            <select name="project" id="project" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->project_name ?? '-' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="button" onclick="get_reports()" class="btn btn-primary mt-4" id="search">Filter</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered" id="allocation-table" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Asset ID</th>
                                            <th>Item Name</th>
                                            <th>Model</th>
                                            <th>Serial Number</th>
                                            <th>Allocated To</th>
                                            <th>Department</th>
                                            <th>Project</th>
                                            <th>Allocated Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <x-footer />
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $(function () {

        get_reports();
    });

    function get_reports() {
        if ($.fn.DataTable.isDataTable('#allocation-table')) {
            $('#allocation-table').DataTable().clear().destroy();
        }
        const classification = $('#classification').val();
        const department = $('#department').val();
        const user_type = $('#user_type').val();
        const employee = $('#employee').val();
        const location = $('#location').val();
        const project = $('#project').val();
        const asset_item = $('#asset_item').val();

        const scrapTable = $('#allocation-table');

        scrapTable.DataTable({
            dom: 'Blfrtip',
            buttons: [
                { extend: 'excelHtml5', title: 'Allocated Items Report'},
                { extend: 'pdfHtml5', title: 'Allocated Items Report'},
                { extend: 'print', title: 'Allocated Items Report'}
            ],
            processing: false,
            serverSide: false,
            ajax: {
                url:'{{ route("assets.reports.allocated-items-data") }}',
                data: {
                    classification: classification,
                    department: department,
                    user_type: user_type,
                    employee: employee,
                    location: location,
                    project: project,
                    asset_item:asset_item
                }
            },
            dataSrc: 'data',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'asset_id' },
                { data: 'item_name' },
                { data: 'model' },
                { data: 'serial_number' },
                { data: 'allocated_to' },
                { data: 'department' },
                { data: 'project' },
                { data: 'allocated_date' }
            ]
        });
    }


// user type

    $(document).ready(function () {
        $('#user_type').on('change', function () {
            let selected = $(this).val();

            // Hide both initially
            $('#employee_box').hide();
            $('#location_box').hide();

            if (selected === 'employee') {
                $('#employee_box').show();
            } else if (selected === 'location') {
                $('#location_box').show();
            }
        });

        // Trigger change on page load if needed (for edit forms)
        $('#user_type').trigger('change');
    });


</script>
@endpush
