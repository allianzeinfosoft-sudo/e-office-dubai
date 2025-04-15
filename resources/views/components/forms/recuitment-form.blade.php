<form action="{{ route('recruitments.store') }} " method="post" id="recuritment-form" >
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <!-- LeftSide  -->
        <div class="col-sm-7">
 
            <div class="row">

                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="jobTitle">Job Title</label>
                        <input type="text" name="jobTitle" id="jobTitle" class="form-control" placeholder="Job Title" />
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="skillRequired">Skills </label>
                            <a class="text-danger" href="javascript:void(0);" onclick="openPopupModal('skills-form', 'skillRequired', 'Add Skills')"><i class="ti ti-plus"></i>New</a>
                        </div>
                        <select class="form-control select2" name="skillRequired[]" id="skillRequired" data-placeholder="Select Skills" multiple>
                            <option value=""></option>
                            @if($skills->isNotEmpty())
                                @foreach($skills as $result)
                                    <option value="{{ $result->id }}"> {{ $result->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
    
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="experience">Experience </label>
                        <select class="form-control select2" name="experience" id="experience" data-placeholder="Select Experience">
                            <option value=""></option>
                            @foreach(config('optionsData.experience') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
    
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="project_id">Job Description</label>
                        <div id="job-description"></div>
                        <input type="hidden" name="jobDescription" id="jobDescription">
                    </div>
                </div>

                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="keyword">Keywords</label>
                            <a class="text-danger" href="javascript:void(0);" onclick="openPopupModal('keywords-rrf-form', 'keyword', 'Add Keywords')"><i class="ti ti-plus"></i>New</a>
                        </div>
                        <select class="form-control select2" name="keyword[]" id="keyword" data-placeholder="Select Keywords" multiple>
                            <option value=""></option>
                            @if($keywords->isNotEmpty())
                                @foreach($keywords as $result)
                                    <option value="{{ $result->id }}"> {{ $result->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
    
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
                    </div>
                </div>

            </div>

        </div>
        <!-- RightRide  -->
        <div class="col-sm-5">
            <div class="row">

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="empId">Project Leader</label>
                        <select class="form-control select2" name="empId" id="empId" data-placeholder="Select Employee">
                            <option value=""></option>
                            @if($projectLeaders->isNotEmpty())
                                @foreach($projectLeaders as $result)
                                    <option value="{{ $result->id }}"> {{ $result->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="rrfDate">Date</label>
                        <input type="date" name="rrfDate" id="rrfDate" class="form-control flatpickr-input" placeholder="RRF Date" />
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="branchId">Branch</label>
                        <select class="form-control select2" name="branchId" id="branchId" data-placeholder="Select Branch">
                            <option value=""></option>
                            @if($branches->isNotEmpty())
                                @foreach($branches as $result)
                                    <option value="{{ $result->id }}"> {{ $result->branch }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="departmentId">Division</label>
                        <select class="form-control select2" name="departmentId" id="departmentId" data-placeholder="Select Department">
                            <option value=""></option>
                            @if($divisions->isNotEmpty())
                                @foreach($divisions as $result)
                                    <option value="{{ $result->id }}"> {{ $result->department }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="positionId">Position</label>
                            <a class="text-danger" href="javascript:void(0);" onclick="openPopupModal('position-form', 'positionId', 'Add Position')"><i class="ti ti-plus"></i>New</a>
                        </div>
                        <select class="form-control select2" name="positionId" id="positionId" data-placeholder="Select Position">
                            <option value=""></option>
                            @if($positions->isNotEmpty())
                                @foreach($positions as $result)
                                    <option value="{{ $result->id }}"> {{ $result->designation }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="projectId">Project Name</label>
                            <a class="text-danger" href="javascript:void(0);" onclick="openPopupModal('project-form', 'projectId', 'Add Projects')"><i class="ti ti-plus"></i>New</a>
                        </div>
                        <select class="form-control select2" name="projectId" id="projectId" data-placeholder="Select Project">
                            <option value=""></option>
                            @if($projects->isNotEmpty())
                                @foreach($projects as $result)
                                    <option value="{{ $result->id }}"> {{ $result->project_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 mb-3">
                    <div class="form-group">
                        <label for="noOfPersons">No of Person </label>
                        <select class="form-control select2" name="noOfPersons" id="noOfPersons" data-placeholder="Select No of Persons">
                            <option value=""></option>
                            @foreach(config('optionsData.noOfPersons') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-8 mb-3">
                    <div class="form-group">
                        <label for="shiftId">Shift </label>
                        <select class="form-control select2" name="shiftId" id="shiftId" data-placeholder="Select Shift">
                            <option value=""></option>
                            @if($shifts->isNotEmpty())
                                @foreach($shifts as $result)
                                    <option value="{{ $result->id }}"> {{ $result->shift_start_time }} - {{ $result->shift_end_time}} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="salaryRange">Salary Range </label>
                        <select class="form-control select2" name="salaryRange" id="salaryRange" data-placeholder="Select Salary Range">
                            <option value=""></option>
                            @foreach(config('optionsData.salaryRange') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="jobType">Job Type </label>
                        <select class="form-control select2" name="jobType" id="jobType" data-placeholder="Select Job Type">
                        <option value=""></option>
                            @foreach(config('optionsData.jobType') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="interviewer">Interviewer </label>
                        <select class="form-control select2" name="interviewer" id="interviewer" data-placeholder="Select Inerviewer">
                            <option value=""></option>
                            @if($interViewer->isNotEmpty())
                                @foreach($interViewer as $result)
                                    <option value="{{ $result->id }}"> {{ $result->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="sittingArragement">Sitting Arrangement fullfill? </label>
                        <select class="form-control select2" name="sittingArragement" id="sittingArragement" data-placeholder="Select Sitting Arragement fullfill">
                            <option value=""></option>
                            @foreach(config('optionsData.sittingArrangement') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="minimumQualification">Minimum Qualification </label>
                            <a class="text-danger" href="javascript:void(0);" onclick="openPopupModal('mini-qualification-form', 'minimumQualification', 'Add Minimum Qualification')" ><i class="ti ti-plus"></i>New</a>
                        </div>
                        <select class="form-control select2" name="minimumQualification" id="minimumQualification" data-placeholder="Select Qualification">
                            <option value=""></option>
                            @if($minimumQualifications->isNotEmpty())
                                @foreach($minimumQualifications as $result)
                                    <option value="{{ $result->id }}"> {{ $result->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="schoolingMedium">Schooling </label>
                        <select class="form-control select2" name="schoolingMedium" id="schoolingMedium" data-placeholder="Select Schooling">
                        <option value=""></option>
                            @foreach(config('optionsData.schoolingMedium') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="graduation">Graduation </label>
                            <a class="text-danger" href="javascript:void(0);" onclick="openPopupModal('graduation-form', 'graduation', 'Add Graduation')"><i class="ti ti-plus"></i>New</a>
                        </div>
                        <select class="form-control select2" name="graduation" id="graduation" data-placeholder="Select Graduation">
                            <option value=""></option>
                            @if($graduations->isNotEmpty())
                                @foreach($graduations as $result)
                                    <option value="{{ $result->id }}"> {{ $result->graduation }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="ageGroup">Age Group </label>
                        <select class="form-control select2" name="ageGroup" id="ageGroup" data-placeholder="Select Age Group">
                            <option value=""></option>
                            @foreach(config('optionsData.ageGroups') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="location">Location </label>
                        <select class="form-control select2" name="location" id="location" data-placeholder="Select Location">
                            <option value=""></option>
                            @foreach(config('optionsData.locations') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="interviewPlace">Interview Place </label>
                        <select class="form-control select2" name="interviewPlace" id="interviewPlace" data-placeholder="Select InterviewPlace">
                        <option value=""></option>
                            @foreach(config('optionsData.interviewPlaces') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="priority">Priority </label>
                        <select class="form-control select2" name="priority" id="priority" data-placeholder="Select Priority">
                            <option value=""></option>
                            @foreach(config('optionsData.priority') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="referralIncentive">Referral Incentive </label>
                        <select class="form-control select2" name="referralIncentive" id="referralIncentive" data-placeholder="Select Referral Incentive">
                            <option value=""></option>
                            @foreach(config('optionsData.referralIncentive') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="requireToAndFroCharge">Require To & Fro Charge? </label>
                        <select class="form-control select2" name="requireToAndFroCharge" id="requireToAndFroCharge" data-placeholder="Select Require To & Fro Charge">
                            <option value=""></option>
                            @foreach(config('optionsData.requierToFroCharge') as $key => $label)
                                <option value="{{ $key }}"> {{ $label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="seekApproval">Seek Approval</label>
                        <select class="form-control select2" name="seekApproval" id="seekApproval" data-placeholder="Select Seek Approval">
                            <option value=""></option>
                            @if($seekApprover->isNotEmpty())
                                @foreach($seekApprover as $result)
                                    <option value="{{ $result->id }}"> {{ $result->full_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
            <button type="button" class="btn btn-primary" onclick="saveAsDraft()"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save as Draft</button>
        </div>   
    </div>
</form>

<div class="modal fade" id="popUpModel" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            <div class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="saveFormDate()"  class="btn btn-primary waves-effect waves-light">Save</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    

    $(function(){
        $('#rrfDate').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });


        
        

    });

    function openPopupModal(formUrl, elementId = 'popupModalBody', modalTitle = 'Modal') {
        var url = @json(route('recruitments.load-modal-form')); // safer way to output route
        $.ajax({
            type: "POST",
            url: url,
            data: {
                formUrl: formUrl,
                elementId: elementId,
                modalTitle: modalTitle,
                _token: '{{ csrf_token() }}'
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const modalEl = document.getElementById('popUpModel');
                    const titleEl = modalEl.querySelector('.modal-title');
                    const bodyEl = $('.modal-body');
                    titleEl.innerText = modalTitle;
                    bodyEl.empty();
                    bodyEl.html(response.html);
                    const modalInstance = new bootstrap.Modal(modalEl);
                    modalInstance.show();
                } else {
                    alert(response.message || 'Error loading form.');
                }
            },
            error: function (xhr) {
                alert('AJAX Error: ' + xhr.statusText);
            }
        });
    }

    function saveFormDate(){
        let form = $('#formData');
        let url = form.attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    form[0].reset();
                    toastr["success"](response.message);
                    const modalEl = document.getElementById('popUpModel');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors && errors.graduation) {
                    toastr["danger"](errors.graduation[0]);
                }
            }
        });
    }

    

</script>
@endpush