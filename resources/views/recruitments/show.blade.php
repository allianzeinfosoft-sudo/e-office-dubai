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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Recuritments /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-8 mb-4">
                            <div class="card">
                                <div class="card-header pb-3 bg-primary">
                                    <h5 class="mb-0 text-white">{{ $recruitment->jobTitle }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3 mt-2 bg-light">
                                        <div class="col-md-6 col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-success me-3 p-2">
                                                <i class="ti ti-map ti-sm"></i>
                                                </div>
                                                <div class="card-info">
                                                    <small>Location</small>
                                                    <h5 class="mb-0">{{ config('optionsData.locations')[$recruitment->location] ?? '' }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                                <i class="ti ti-user ti-sm"></i>
                                                </div>
                                                <div class="card-info">
                                                    <small>Posted By</small>
                                                <h5 class="mb-0">{{ $recruitment->postedBy->full_name }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-warning me-3 p-2">
                                                <i class="ti ti-briefcase ti-sm"></i>
                                                </div>
                                                <div class="card-info">
                                                    <small>Position(s)</small>
                                                <h5 class="mb-0">{{ $recruitment->designation->designation }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                                <i class="ti ti-share ti-sm"></i>
                                                </div>
                                                <div class="card-info">
                                                    <small>Department</small>
                                                <h5 class="mb-0">{{ $recruitment->department->department }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <h5>Job Description</h5>
                                            <blockquote class="blockquote mt-3 alert alert-success">
                                                {!! $recruitment->jobDescription !!}
                                            </blockquote>    
                                        </div>

                                        <div class="col-12 mt-3">
                                            <div class="row">
                                                <div class="col-12 d-flex flex-wrap gap-2">
                                                    <button type="button" class="btn btn-primary btn-lg">Branch : {{ $recruitment->branch->branch }} </button>
                                                    <button type="button" class="btn btn-info btn-lg">Salary :  {{ config('optionsData.salaryRange')[$recruitment->salaryRange] ?? '' }} </button>
                                                    <button type="button" class="btn btn-dark btn-lg">No. of Persons : {{ config('optionsData.noOfPersons')[$recruitment->noOfPersons] ?? '' }} </button>
                                                    <button type="button" class="btn btn-warning btn-lg">Job Type : {{ config('optionsData.jobType')[$recruitment->jobType] ?? '' }} </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="divider">
                                            <div class="divider-text"><i class="ti ti-crown"></i></div>
                                        </div>

                                        <div class="col-6 mt-3">
                                            <h5 class="text-center">Interviewer</h5>
                                            <div class="user-avatar-section">
                                                <div class="d-flex align-items-center flex-column">
                                                    <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ $recruitment->interViewer->profile_image ? asset('storage/' . $recruitment->interViewer->profile_image) : asset('assets/img/avatars/1.png') }}" height="150" width="150" alt="User avatar">
                                                    <div class="user-info text-center">
                                                        <h4 class="mb-2">{{ $recruitment->interViewer->full_name }}</h4>
                                                        <span class="badge bg-label-secondary mt-1">{{ $recruitment->interViewer->roles }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6 mt-3">
                                            <h5 class="text-center">Seek Approval</h5>
                                            <div class="user-avatar-section">
                                                <div class="d-flex align-items-center flex-column">
                                                    <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ $recruitment->seekApprover->profile_image ? asset('storage/' . $recruitment->seekApprover->profile_image) : asset('assets/img/avatars/1.png') }}" height="150" width="150" alt="User avatar">
                                                    <div class="user-info text-center">
                                                        <h4 class="mb-2">{{ $recruitment->seekApprover->full_name }}</h4>
                                                        <span class="badge bg-label-secondary mt-1">{{ $recruitment->seekApprover->roles }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="divider">
                                            <div class="divider-text"><i class="ti ti-crown"></i></div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Shift</th>
                                                        <th>Project Name</th>
                                                        <th>Minimum Qualification</th>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-label-primary"> {{ $recruitment->workShift->shift_start_time }} - {{ $recruitment->workShift->shift_end_time }} </span></td>
                                                        <td><span class="badge bg-label-danger">{{ $recruitment->project->project_name }} </span></td>
                                                        <td><span class="badge bg-label-success">{{ $recruitment->minimumQualifications->name }} </span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Skill required</th>
                                                        <th>Sitting arrangement fulfill?</th>
                                                        <th>Experience</th>
                                                    </tr>
                                                    <tr>
                                                        <td> @foreach ($recruitment->skill_names as $skill) <span class="badge bg-label-primary"> {{ $skill }} </span>  @endforeach </td>
                                                        <td><span class="badge bg-label-danger">{{ config('optionsData.sittingArrangement')[$recruitment->sittingArragement] ?? '' }} </span></td>
                                                        <td><span class="badge bg-label-success">{{ config('optionsData.experience')[$recruitment->experience] ?? '' }} </span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Graduation Medium</th>
                                                        <th>Age group</th>
                                                        <th>Interview Place</th>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-label-primary"> {{ $recruitment->graduationMedium->graduation }} </span></td>
                                                        <td><span class="badge bg-label-danger"> {{ config('optionsData.ageGroups')[$recruitment->ageGroup] ?? '' }} </span></td>
                                                        <td><span class="badge bg-label-success"> {{ config('optionsData.interviewPlaces')[$recruitment->interviewPlace] ?? '' }} </span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Schooling Medium</th>
                                                        <th>Required Person Priority</th>
                                                        <th>Referral Incentive</th>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-label-primary"> {{ config('optionsData.schoolingMedium')[$recruitment->schoolingMedium] ?? '' }} </span></td>
                                                        <td><span class="badge bg-label-danger"> {{ config('optionsData.priority')[$recruitment->priority] ?? '' }} </span></td>
                                                        <td><span class="badge bg-label-success"> {{ config('optionsData.referralIncentive')[$recruitment->referral] ?? '' }} </span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Require To & Fro Charge</th>
                                                        <th>Keyword</th>
                                                        <th>Remarks:</th>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-label-primary"> {{ config('optionsData.requierToFroCharge')[$recruitment->requireToAndFroCharge] ?? '' }} </span></td>
                                                        <td>
                                                            @foreach ($recruitment->Keywords as $keyword) <span class="badge bg-label-danger"> {{ $keyword }} </span>  @endforeach</td>
                                                        <td><span class="badge bg-label-success"> {{ $recruitment->remarks }} </span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>


                                </div>

                            </div>
                            
                        </div>

                        <div class="col-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">                            
                                        <h5 class="card-title">Application Status : </h5>
                                        @php 
                                            $colors = array(0 => 'warning', 1 => 'danger', 2 => 'primary', 3 => 'info', 4 => 'success');
                                        @endphp
                                        <span class="badge bg-{{ $colors[$recruitment->status ?? 0] }} bg-glow fs-5">
                                            {{ config('optionsData.applicationStatus')[$recruitment->status] ?? 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <form action="{{ route('recruitments.update-status') }}" id="application-status-form" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $recruitment->id }}">
                                            <div class="col-sm-12 mb-3">
                                                <div class="form-group">
                                                    <label for="status">Change Status </label>
                                                    <select class="form-control                                                                                                              select2" name="status" id="status">
                                                        <option value=""></option>
                                                        @foreach(config('optionsData.applicationStatus') as $key => $label)
                                                            <option value="{{ $key }}"> {{ $label }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                                <div class="form-group">
                                                    <label for="remarks">Reason</label>
                                                    <textarea class="form-control" name="status_reason" id="status_reason"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                            <button type="button" id="change-status-btn" class="btn btn-primary">Change</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

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
@stop


@push('js')
<script>
    $(function(){
        $('#application-status-form button').on('click', function(e) {
            e.preventDefault();

            let form = $('#application-status-form');
            let formData = form.serialize();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    form[0].reset();
                    toastr["success"](response.message);
                    window.location.reload();
                    // you can also refresh table or status badge here.
                },
                error: function(xhr) {
                    alert('Failed to update status!');
                }
            });
        });
    });
</script>
@endpush