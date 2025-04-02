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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="addProductivityTragets()">
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
                                        <th>Project</th>
                                        <th>Task</th>
                                        <th>Target Date</th>
                                        <th>Rec/hr</th>
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
                <div class="content-backdrop fade"></div>
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
@stop


@push('js')
<script>
    $(function(){
        
    });

    function addProductivityTragets() {
        var offcanvasElement = $('#add_project_tasks_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();

        $('#add_project_tasks_offcanvas #membersContainer').empty();
        $('#add_project_tasks_offcanvas #membersContainer').html(`<label for="members">Members</label>
        <select class="form-control" name="members[]" id="members" data-placeholder="Select Members" multiple="multiple">
        <option value=""></option>
        </select>`);
    }
</script>
@endpush