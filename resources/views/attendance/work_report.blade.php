@extends('layouts.app')

@section('css')
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- Menu section -->
        <x-menu />

        <!-- Page content -->
        <div class="layout-page">

            <!-- Header Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Attendance /</span>{{ $meta_title }}</h4>

                    <div class="row">

                        <div class="col-lg-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <small class="d-block mb-1"> You must be enter your work report</small>
                                    </div>
                                    <h4 class="card-title mb-1"> <i class="ti ti-printer ti-sm"></i> {{ $meta_title }}</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="Project">Project</label>
                                                    <select name="projects" id="projects" data-placeholder="Select Project" class="form-control select2">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>   

                                            <div class="col-sm-3 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="type_of_work">Type of Work</label>
                                                    <select name="type_of_work" data-placeholder="Select Type of Work" id="type_of_work" class="form-control select2">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="Project">Total Records / Tasks</label>
                                                    <input type="text" name="" id="" placeholder="Totla Records / Tasks" class="form-control" />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="Project">Productivity Per Hour</label>
                                                    <input type="text" name="" id="" placeholder="Productivity per hour" class="form-control" />
                                                </div>
                                            </div>    

                                            <div class="col-sm-2 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="Project">No. of Hours</label>
                                                    <input type="time" name="" id="" placeholder="No. of Hours" value="{{ date('H:i', strtotime('now')) }}" class="form-control" required />
                                                </div>
                                            </div>    

                                            <div class="col-sm-12 mb-2 g-2">
                                                <div class="form-group">
                                                    <label for="Project">Comments</label>
                                                    <textarea name="" id="" class="form-control" rows="5"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-12 mb-2 g-2 d-flex justify-content-end">
                                                <button type="button" class="btn btn-primary"><i class="ti ti-plus"></i> Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 mb-2">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th width="11.11%">Project Name</th>
                                                            <th width="11.11%">Type of Work</th>
                                                            <th width="11.11%">Total Records / Tasks</th>
                                                            <th width="11.11%">No. of Hours</th>
                                                            <th width="11.11%">Productivity / Hour</th>
                                                            <th width="11.11%">Grade</th>
                                                            <th width="11.11%">Performance</th>
                                                            <th width="11.11%">Comments</th>
                                                            <th width="11.11%">Action</th>
                                                        </tr>                                                    
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody> 
                                                </table>
                                            </div>
                                        </div>                                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    $(function() {
        $('.select2').select2();
    });
</script>
@stop