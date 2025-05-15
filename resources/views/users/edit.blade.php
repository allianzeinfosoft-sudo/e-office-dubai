@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
      <!-- Menu -->
      <x-menu /> <!-- Load the menu component here -->

      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <x-header />
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->



          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">New User</h4>
            <form action="{{ route('users.update', $user->id) }}" method="POST" name="userForm" id="userFormId" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row">
              <!-- Form controls -->
              <div class="col-md-12">
                <div class="card mb-4">
                  <h5 class="card-header">Personal Informations</h5>
                  <div class="row card-body">
                    <div class="col-md-4 mb-3">
                      <label for="employee_id" class="form-label">Employoee ID:</label>
                      <input type="hidden" name="user_id"  value="{{ $user->id ?? '' }}">
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="employeeID" id="employeeID" value="{{ $user->employee?->employeeID ?? '' }}" placeholder="Enter username" aria-describedby="employeeID" readonly/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="username" class="form-label">Username:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username',$user->username) ?? '' }}" placeholder="Enter username" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="email_id" class="form-label">Email ID:</label>
                        <div class="input-group input-group-merge">
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email',$user->email) ?? '' }}" placeholder="Enter email" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="fullname" class="form-label">Full Name:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name',$user->employee?->full_name) ?? ''  }}" placeholder="Enter fullname"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="phonenumber" class="form-label">Phone Number:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="{{ old('phonenumber',$user->employee?->phonenumber) ?? ''  }}" placeholder="Enter phone number"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="reporting_to" class="form-label">Reporting To:</label>
                        <select id="reporting_to" name="reporting_to" class="select2 form-select form-select-lg" data-allow-clear="true">
                            <option value="">Select reporting person</option>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->user_id ?? '' }}"  {{ old('reporting_to', $user->employee?->reporting_to) == $employee->user_id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="personal_email" class="form-label">Personal Email:</label>
                      <div class="input-group input-group-merge">
                      <input type="email" class="form-control" id="personal_email" name="personal_email" value="{{ old('personal_email', $user->employee?->personal_email) }}" placeholder="Enter personal email"/>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="gender" class="form-label">Gender:</label>
                      <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input" type="radio"   id="inlineRadio1" name="gender" value="male" {{ old('gender', $user->employee?->gender) == 'male' ? 'checked' : '' }}/>
                        <label class="form-check-label" for="inlineRadio1">Male</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"  id="inlineRadio2" name="gender" value="female"  {{ old('gender', $user->employee?->gender) == 'female' ? 'checked' : '' }}/>
                        <label class="form-check-label" for="inlineRadio2">Female</label>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="blood_group" class="form-label">Blood Group:</label>
                        <select id="blood_group" name="blood_group" class="select2 form-select form-select-lg" data-allow-clear="true">
                          <option value="" disabled {{ old('blood_group', $user->employee?->blood_group) ? '' : 'selected' }}>Please select</option>
                          @foreach (['O-ve', 'O+ve', 'A-ve', 'A+ve', 'B-ve', 'B+ve', 'AB-ve', 'AB+ve'] as $bloodType)
                              <option value="{{ $bloodType }}" {{ old('blood_group', $user->employee?->blood_group) === $bloodType ? 'selected' : '' }}>
                                  {{ $bloodType }}
                              </option>
                          @endforeach
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="qualification" class="form-label">Qualification:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="qualification" name="qualification" value="{{ old('qualification',$user->employee?->qualification) }}" placeholder="Enter qualification" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="esi_no" class="form-label">ESI No:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="esi_no" name="esi_no" value="{{ old('esi_no',$user->employee?->esi_no) ?? '' }}" placeholder="Enter ESI No" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="aadhaar" class="form-label">Aadhaar:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="aadhaar" name="aadhaar" value="{{ old('aadhar',$user->employee?->aadhaar) ?? '' }}" placeholder="Enter Aadhaar No"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="pf_no" class="form-label">PF No:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="pf_no" name="pf_no" value="{{ old('pf_no', $user->employee?->pf_no) ?? '' }}" placeholder="Enter PF No"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="electoral_id" class="form-label">Electoral ID:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="electoral_id" name="electoral_id" value="{{ old('electoral_id', $user->employee?->electoral_id) }}" placeholder="Enter Electoral ID"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="pan" class="form-label">PAN:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="pan" name="pan" value="{{ old('pan',$user->employee?->pan) ?? '' }}" placeholder="Enter PAN"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="date_of_birth" class="form-label">Date of Birth:</label>
                      <div class="input-group input-group-merge">
                      <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $user->employee?->dob) ?? '' }}" placeholder="Enter Date of Birth"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="group" class="form-label">Group:</label>
                      <select id="group" name="group" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value="">Select Group</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ old('group', $user->employee?->group) == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="group" class="form-label">Address:</label>
                      <div class="input-group input-group-merge">
                      <textarea type="text" class="form-control" id="address" name="address" placeholder="Select Address" rows="3">
                        {{ old('address', $user->employee?->address) ?? '' }}
                      </textarea>
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
                      <img id="imagePreview" src="{{ asset('storage/' . $user->employee?->profile_image ?? '' ) }}" alt="" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;   border: 2px solid #ddd;"/>
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
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->employee?->mobile_number) }}" placeholder="Enter mobile number"/>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="mobile_relationship" class="form-label">Relationship:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="mobile_relationship" id="mobile_relationship" value="{{ old('mobile_relationship', $user->employee?->mobile_relationship) ?? ''}}" placeholder="Enter relationship" />
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="landline" class="form-label">Landline:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" name="landline" id="landline" value="{{ old('landline', $user->employee?->landline) ?? '' }}" placeholder="Enter landline"/>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="landline_relationship" class="form-label">Relationship:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="landline_relationship" id="landline_relationship" value="{{ old('landline_relationship') ?? '' }}" placeholder="Enter relationship"/>
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
                            <option value="" disabled {{ old('department_id', $user->employee?->department_id ?? '') ? '' : 'selected' }}>
                                Select Department
                            </option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $user->employee?->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                    {{ $department->department ?? 'Unnamed Department' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="designation" class="form-label">Designation:</label>
                        <select id="designation_id" name="designation_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                            @foreach ($designations as $value)
                                <option value="{{ $value->id }}" {{ old('designation_id', $user->employee?->designation_id) == $value->id ? 'selected' : '' }}>{{ $value->designation ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="smallInput" class="form-label">Join Date:</label>
                      <div class="input-group input-group-merge">
                        <input class="form-control" type="date" id="join_date" name="join_date" value="{{ $user->employee?->join_date ?? '0' }}"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="shift_id" class="form-label">Shift No:</label>
                        <select id="shift_id" name="shift_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                         @foreach ($work_shifts as $work_shift)
                            <option value="{{  $work_shift->id ?? '' }}" {{ old('shift_id', $user->employee?->shift_id) == $work_shift->id ? 'selected' : '' }}>{{  $work_shift->shift_id ?? '' }} </option>
                         @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="login_limited_time" class="form-label">Login Limited Time:</label>
                        <div class="input-group input-group-merge">
                          <input type="time" class="form-control" id="login_limited_time" step="1" name="login_limited_time" value="{{ old('login_limited_time', $user->employee?->login_limited_time) ?? '' }}" placeholder="Enter login limited time">
                        </div>
                      </div>

                    {{-- <div class="col-md-4 mb-3">
                      <label for="role" class="form-label">Role:</label>
                        <select id="role" name="role" class="select2 form-select form-select-lg" data-allow-clear="true">
                            <option selected value="">Please select</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->employee?->role) == $role->name ? 'selected' : '' }} > {{ $role->name ?? '' }} </option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Holiday Group:</label>

                          <select id="holidayGroup" name="holidayGroup" class="select2 form-select form-select-lg" data-allow-clear="true">
                              <option selected value="">Please select</option>
                                  @foreach (config('optionsData.holiday_group') as $key => $value)
                                      <option value="{{ $key }}">{{ $value ?? 'N/A' }}</option>
                                  @endforeach
                          </select>

                      </div>

                    <div class="col-md-4 mb-3">
                      <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="select2 form-select form-select-lg" data-allow-clear="true">
                          <option selected value="">Please select</option>
                           @foreach ($user_statuses as $user_status)
                                <option value="{{ $user_status->id }}" {{ old('status', $user->employee?->status) == $user_status->id ? 'selected' : '' }}> {{ $user_status->status_name ?? '' }} </option>
                           @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-4 mb-3">
                      <label for="leave_carry_info" class="form-label">Leave Carry Info:</label>
                      <div class="input-group input-group-merge">
                        <input id="leave_carry_info" name="leave_carry_info" class="form-control" type="text" value="{{ old('leave_carry_info', $user->employee?->leave_carry_info) ?? '' }}" placeholder="Enter leave carry info" />
                      </div>
                    </div> --}}
                    <div class="col-md-4 mb-3">
                      <label for="appointment_status" class="form-label">Appointment Status:</label>
                        <select id="appointment_status" name="appointment_status" class="select2 form-select form-select-lg" data-allow-clear="true">
                          <option selected value="">Please select</option>
                          @foreach (['probation','confirmed'] as $appointment_status)
                              <option value="{{ $appointment_status }}" {{ old('appointment', $user->employee?->appointment_status ) == $appointment_status ? 'selected' : '' }} > {{ $appointment_status }} </option>
                          @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="team_lead" class="form-label">Team Lead:</label>
                        <select id="team_lead" name="team_lead" class="select2 form-select form-select-lg" data-allow-clear="true">
                          <option selected value="">Please select</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->user_id ?? '' }}" {{ old('team_lead', $user->employee?->team_lead) == $employee->user_id ? 'selected' : '' }}>{{ $employee->full_name ?? '' }}</option>
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
                        <input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ old('bank_name', $user->employee?->bank_name) ?? '' }}" placeholder="Enter bank name"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="branch" class="form-label">Branch:</label>
                      <div class="input-group input-group-merge">
                          <input type="text" class="form-control" name="bank_branch" id="bank_branch" value="{{ old('bank_branch', $user->employee?->bank_branch) ?? '' }}" placeholder="Enter bank_branch" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="beneficiary_name" class="form-label">Beneficiary Name:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="beneficiary_name" id="beneficiary_name" value="{{ old('beneficiary_name', $user->employee?->beneficiary_name) ?? '' }}" placeholder="Enter beneficiary"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="account_number" class="form-label">Account Number:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="account_number" id="account_number" value="{{ old('account_number', $user->employee?->account_number) ?? '' }}" placeholder="Enter account number"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ifsc" class="form-label">IFSC:</label>
                        <div class="input-group input-group-merge">
                          <input type="text" class="form-control" name="ifsc" id="ifsc" value="{{ old('ifsc', $user->employee?->ifsc) ?? '' }}" placeholder="Enter IFSC"/>
                        </div>
                      </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-12">
                <div class="card">
                  <div class="row card-body">
                    <div class="col-md-4 mb-3">
                      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit waves-effect waves-light">Submit</button>
                      <button type="reset" class="btn btn-label-secondary waves-effect" data-bs-dismiss="offcanvas">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          </div>


          <!-- / Content -->
          <!-- Footer -->
          <x-footer />
          <!-- / Footer -->
          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
@endsection

<script>
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
