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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Works /</span> {{ $meta_title }}</h4>

                    <div class="row">

                        <div class="col-lg-12 mb-4" id="sdu_project_report">
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
                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="project_name" class="form-label">Project Code</label>
                                                    <select name="project_name" id="project_name" data-placeholder="Select Project" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>   

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="type_of_work" class="form-label">Projects</label>
                                                    <select name="type_of_work" data-placeholder="Select Type of Work" id="type_of_work" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="type_of_work" class="form-label">Sub Tasks</label>
                                                    <select name="type_of_work" data-placeholder="Select Type of Work" id="type_of_work" class="select2 form-select" data-allow-clear="true">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>   

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="total_time" class="form-label">No. of Hours</label>
                                                    <input type="time" name="total_time" id="total_time" placeholder="No. of Hours" step="2" value="" class="form-control" required />
                                                </div>
                                            </div>    

                                            <div class="col-sm-12 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="comments" class="form-label">Comments</label>
                                                    <textarea name="comments" id="comments" class="form-control" rows="5"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12 mb-2 g-2 d-flex justify-content-end">
                                                <button type="button" id="submitForm" class="btn btn-primary"><i class="ti ti-plus"></i> Add</button>
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
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
<script>
    $(function(){
        
    });
</script>
@stop