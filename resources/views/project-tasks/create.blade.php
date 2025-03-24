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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Project Task \</span> {{ $meta_title }}</h4>
                    
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('tasks-project.store') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12 mb-3">
                                                <div class="form-group">
                                                    <label for="task_name">Task Name</label>
                                                    <input type="text" name="task_name" id="task_name" class="form-control" placeholder="Task Name" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-12 mb-3">
                                                <div class="form-group">
                                                    <label for="project_id">Projects</label>
                                                    <select class="form-control select2" name="project_id" id="project_id" data-placeholder="Select Project">
                                                        <option value=""></option>
                                                        @if($projects->isNotEmpty()) 
                                                            @foreach($projects as $project) 
                                                                <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="pr_task_id">Pr Task</label>
                                                    <input type="text" name="pr_task_id" id="pr_task_id" class="form-control" placeholder="Pr Task Id" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="pr_sub_task_id">Pr Sub Task</label>
                                                    <input type="text" name="pr_sub_task_id" id="pr_sub_task_id" class="form-control" placeholder="Pr Sub Task Id" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-12 mb-3">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>  Save</button>
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
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
<script>
    $(function(){
        $('.select2').select2();

        $('.datepicker').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'j-m-Y',
            dateFormat: 'Y-m-d'
        });
    });
</script>
@stop