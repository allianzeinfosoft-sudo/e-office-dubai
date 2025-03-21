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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Projects \</span> {{ $meta_title }}</h4>
                    
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('project.store') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12 mb-3">
                                                <div class="form-group">
                                                    <label for="project_name">Project Name</label>
                                                    <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Project Name" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="project_add_person">Add By</label>
                                                    <select class="form-control select2" name="project_add_person" id="project_add_person" data-placeholder="Select Project">
                                                        <option value=""></option>
                                                        @if($users->isNotEmpty()) 
                                                            @foreach($users as $user) 
                                                                <option value="{{ $user->id }}">{{ $user->username }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="prdepartment_idoject_name">Department</label>
                                                    <select class="form-control select2" name="department_id" id="department_id" data-placeholder="Select Department">
                                                        <option value=""></option>
                                                        @if($departments->isNotEmpty()) 
                                                            @foreach($departments as $department) 
                                                                <option value="{{ $department->id }}">{{ $department->department }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="Start Date" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="end_date">End Date</label>
                                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker" placeholder="End Date" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="total_hours">Total Hours</label>
                                                    <input type="text" name="total_hours" id="total_hours" class="form-control" placeholder="Total Hours" />
                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 mb-3">
                                                <div class="form-group">
                                                    <label for="total_day">Total days</label>
                                                    <input type="text" name="total_day" id="total_day" class="form-control" placeholder="Total Days" />
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