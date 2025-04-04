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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> User /</span> {{ $meta_title }}</h4>

                    @if ($errors->any())
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!--  Form Start -->
                    <form action="{{ route('users.storeOrUpdate', $user->id ?? '') }}" method="POST" name="userForm" id="userProfileForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ $user->id ?? '' }}" />
                        <div class="row">
                            
                        <!-- Form controls -->
                         <div class="col-md-12">
                              <div class="card mb-4">
                                <h5 class="card-header">Personal Informations</h5>
                                <div class="row card-body">
                                    <div class="col-md-4 mb-3">
                                        <label for="employee_id" class="form-label">Employoee ID:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control disabled bg-light" name="employeeID" id="employeeID" value="{{ old('employeeID', $user->employeeID ?? $nextEmployeeId)}}" placeholder="Enter username" aria-describedby="employeeID" readonly/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="username" class="form-label">Username:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control bg-light disabled" id="username" name="username" value="{{ old('username', $loginUser->username ?? '')}}" placeholder="Enter username" readonly />
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="email_id" class="form-label">Email ID:</label>
                                        <div class="input-group input-group-merge">
                                            <input class="form-control desabled bg-light" type="email" id="email_id" name="email" value="{{ old('email', $loginUser->email ?? '')}}" placeholder="Enter email" readonly />
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="fullname" class="form-label">Full Name:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name ?? '')}}" placeholder="Enter fullname"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="phonenumber" class="form-label">Phone Number:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="{{ old('phonenumber', $user->phonenumber ?? '') }}" placeholder="Enter phone number"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="reporting_to" class="form-label">Reporting To:</label>
                                        <select id="reporting_to" name="reporting_to" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">Select reporting person</option>
                                            @if($employees->isnotempty())
                                                @foreach ($employees as $employee)
                                                <option value="{{ $employee->user_id }}" {{ (isset($user) && $user->reporting_to == $employee->user_id) ? 'selected' : '' }} >{{ $employee->full_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="personal_email" class="form-label">Personal Email:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="email" class="form-control" id="personal_email" name="personal_email" value="{{ old('personal_email', $user->personal_email ?? '')}}" placeholder="Enter personal email"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="radio" id="inlineRadio1" name="gender" value="male" {{ old('gender', isset($user) && $user->gender == 'male' ? 'checked' : '') }} />
                                            <label class="form-check-label" for="inlineRadio1">Male</label>
                                        </div>
                      
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"  id="inlineRadio2" name="gender" value="female" {{ old('gender', isset($user) &&  $user->gender == 'female' ? 'checked' : '') }} />
                                            <label class="form-check-label" for="inlineRadio2">Female</label>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="blood_group" class="form-label">Blood Group:</label>
                                        <select id="blood_group" name="blood_group" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">Please select</option>
                                            @foreach (['A+ve','A-ve','AB+ve','AB-ve', 'B+ve', 'B-ve', 'O+ve', 'O-ve'] as $bloodType)
                                                <option value="{{ $bloodType }}" {{ old('blood_group', isset($user) && $user->blood_group) == $bloodType ? 'selected' : '' }}>{{ $bloodType }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="qualification" class="form-label">Qualification:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="qualification" name="qualification" value="{{ old('qualification', $user->qualification ?? '')}}" placeholder="Enter qualification" />
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="esi_no" class="form-label">ESI No:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="esi_no" name="esi_no" value="{{ old('esi_no', $user->esi_no ?? '') }}" placeholder="Enter ESI No" />
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="aadhaar" class="form-label">Aadhaar:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="aadhaar" name="aadhaar" value="{{ old('aadhaar', $user->aadhaar ?? '') }}" placeholder="Enter Aadhaar No"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="pf_no" class="form-label">PF No:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="pf_no" name="pf_no" value="{{ old('pf_no', $user->pf_no ?? '') }}" placeholder="Enter PF No"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="electoral_id" class="form-label">Electoral ID:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="electoral_id" name="electoral_id" value=" {{ old('electoral_id', $user->electoral_id ?? '')}} " placeholder="Enter Electoral ID"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="pan" class="form-label">PAN:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="pan" name="pan" value="{{ old('pan', $user->pan ?? '') }}" placeholder="Enter PAN"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $user->dob ?? '') }}" placeholder="Enter Date of Birth"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="group" class="form-label">Group:</label>
                                        <select id="group" name="group" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">Select Group</option>
                                            @foreach (['G1','G2','G3','G4','G5'] as $group)
                                            <option value="{{ $group }}" {{ old( 'group', (isset($user) && $user->group == $group) ? 'selected' : '' ) }} > {{ $group }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="group" class="form-label">Address:</label>
                                        <div class="input-group input-group-merge">
                                            <textarea type="text" class="form-control" id="address" name="address" placeholder="Select Address" rows="3">{{ old('address', $user->address ?? '')}} </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
              
                        <!-- Input Sizing -->
                         <div class="col-md-6">
                            <div class="card mb-4">
                                <h5 class="card-header">Profile Image</h5>
                                <div class="card-body">
                                    <div class="mt-3 d-flex justify-content-center align-items-center" style="background-color: #625acc; height: 200px;">
                                        <img id="imagePreview" src="{{ isset($user) && $user->profile_image ? asset('storage/' . $user->profile_image) : '../../assets/img/avatars/1.png' }}" alt="" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;   border: 2px solid #ddd;"/>
                                    </div>
                                    <div class="mb-3 mt-15">
                                        <div class="input-group input-group-merge">
                                            <input class="form-control" type="file" id="formFile" name="profile_image" onchange="previewImage(event)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
              
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <h5 class="card-header">Emergency Contact Information</h5>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="mobile_number" class="form-label">Mobile No:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number ?? '' )}}" placeholder="Enter mobile number"/>
                                        </div>
                                    </div>
                    
                                    <div class="mb-3">
                                        <label for="mobile_relationship" class="form-label">Relationship:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="mobile_relationship" id="mobile_relationship" value="{{ old('mobile_relationship', $user->mobile_relationship ?? '' )}}" placeholder="Enter relationship" />
                                        </div>
                                    </div>
                    
                                    <div class="mb-3">
                                        <label for="landline" class="form-label">Landline:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="landline" id="landline" value="{{ old('landline', $user->landline ?? '' )}}" placeholder="Enter landline"/>
                                        </div>
                                    </div>
                    
                                    <div class="mb-3">
                                        <label for="landline_relationship" class="form-label">Relationship:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="landline_relationship" id="landline_relationship" value="{{ old('landline_relationship', $user->landline_relationship ?? '' ) }}" placeholder="Enter relationship"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
              
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <h5 class="card-header">Office Information</h5>
                                <div class="row card-body">
                                    <div class="col-md-4 mb-3">
                                        <label for="department" class="form-label">Department:</label>
                                        <select id="department_id" name="department_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id', $user->department_id ?? '' == $department->id ? 'selected' : '') }}> {{ $department->department ?? '' }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="designation" class="form-label">Designation:</label>
                                        <select id="designation_id" name="designation_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            @foreach ($designations as $value)
                                            <option value="{{ $value->id }}" {{ old('designation_id', $user->designation_id ?? '' == $value->id ? 'selected' : '') }}> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="smallInput" class="form-label">Join Date:</label>
                                        <div class="input-group input-group-merge">
                                            <input class="form-control" type="date" id="join_date" name="join_date" value="{{ old('join_date', $user->join_date ?? '' )}}"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="shift_id" class="form-label">Shift No:</label>
                                        <select id="shift_id" name="shift_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            @foreach ($work_shifts as $work_shift)
                                            <option selected value="{{  $work_shift->id ?? '' }}" {{ old('shift_id', $user->shift_id ?? ''== $work_shift->id ? 'selected' : '') }} >{{  $work_shift->shift_id ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="login_limited_time" class="form-label">Login Limited Time:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="time" class="form-control" id="login_limited_time" name="login_limited_time" value="{{ old('login_limited_time', $user->login_limited_time ?? '' ) }}" placeholder="Enter login limited time">
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="role" class="form-label">Role:</label>
                                        <select id="role" name="role" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option selected value="">Please select</option>
                                            @foreach ($roles as $role)
                                            <option selected value="{{ $role->name }}" {{ old('role', $user->role ?? '' == $role->name ? 'selected' : '') }} >{{ $role->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="status" class="form-label">Status:</label>
                                        <select id="status" name="status" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option selected value="">Please select</option>
                                            @foreach ($user_statuses as $user_status)
                                            <option value="{{ $user_status->id }}" {{ old('status', $user->status ?? '' == $user_status->id ? 'selected' : '') }}>{{ $user_status->status_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="appointment_status" class="form-label">Appointment Status:</label>
                                        <select id="appointment_status" name="appointment_status" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option selected value="">Please select</option>
                                            @foreach (['probation','confirmed'] as $appointment_status)
                                            <option value="{{ $appointment_status }}" {{ old('appointment_status', $user->appointment_status ?? '' == $appointment_status ? 'selected' : '') }}> {{ $appointment_status }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="team_lead" class="form-label">Team Lead:</label>
                                        <select id="team_lead" name="team_lead" class="select2 form-select form-select-lg" data-allow-clear="true">
                                            <option selected value="">Please select</option>
                                            @foreach ($employees as $employee)
                                            <option value="{{ $employee->user_id ?? '' }}" {{ old('team_lead', $user->team_lead ?? '' == $employee->user_id ? 'selected' : '') }}>{{ $employee->full_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
              
                        <div class="col-xl-12">
                            <div class="card">
                                <h5 class="card-header">Bank Information-Not Sharable</h5>
                                <div class="row card-body">
                                    <div class="col-md-4 mb-3">
                                        <label for="bank_name" class="form-label">Bank Name:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ old('bank_name', $user->bank_name ?? '') }}" placeholder="Enter bank name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="branch" class="form-label">Branch:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="bank_branch" id="bank_branch" value="{{ old('bank_branch', $user->bank_branch ?? '') }}" placeholder="Enter bank_branch" />
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="beneficiary_name" class="form-label">Beneficiary Name:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="beneficiary_name" id="beneficiary_name" value="{{ old('beneficiary_name', $user->beneficiary_name ?? '') }}" placeholder="Enter beneficiary"/>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-4 mb-3">
                                        <label for="account_number" class="form-label">Account Number:</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" class="form-control" name="account_number" id="account_number" value="{{ old('account_number', $user->account_number ?? '') }}" placeholder="Enter account number"/>
                                        </div>
                                    </div>
                                </div>
                  
                                <div class="card-footer">
                                    <div class="col-md-4 mb-3">
                                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit waves-effect waves-light">Submit</button>
                                        <button type="reset" class="btn btn-label-secondary waves-effect" data-bs-dismiss="offcanvas">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Form Ends -->

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
        
    });

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById("imagePreview");
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
