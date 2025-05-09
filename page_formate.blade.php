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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Project /</span> {{ $meta_title }}</h4>

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
        
    });
</script>
@endpush