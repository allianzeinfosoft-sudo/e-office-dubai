@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
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
            <form action="{{ route('users.store') }}" method="POST" name="userForm" id="userFormId" enctype="multipart/form-data" onsubmit="return false">
              @csrf
            <div class="row">
              <!-- Form controls -->
              <div class="col-md-12">
                <div class="card mb-4">
                  <h5 class="card-header">Personal Informations</h5>
                  <div class="row card-body">
                    <div class="col-md-4 mb-3">
                      <label for="employee_id" class="form-label">Employoee ID:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="employeeID" id="employeeID" value="{{ $nextEmployeeId ?? '' }}" placeholder="Enter username" aria-describedby="employeeID" readonly/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="username" class="form-label">Username:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="email_id" class="form-label">Email ID:</label>
                        <div class="input-group input-group-merge">
                        <input class="form-control" type="email" id="email_id" name="email" placeholder="Enter email" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="fullname" class="form-label">Full Name:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter fullname"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="phonenumber" class="form-label">Phone Number:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="phonenumber" name="phonenumber" placeholder="Enter phone number"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="reporting_to" class="form-label">Reporting To:</label>
                      <div class="input-group input-group-merge">
                       <select class="form-select" id="reporting_to" name="reporting_to">
                        <option value="">Select reporting person</option>
                        @foreach ($employees as $employee)
                          <option value="{{ $employee->user_id ?? '' }}">{{ $employee->full_name ?? '' }}</option>
                        @endforeach
                      </select>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="personal_email" class="form-label">Personal Email:</label>
                      <div class="input-group input-group-merge">
                      <input type="email" class="form-control" id="personal_email" name="personal_email" placeholder="Enter personal email"/>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="gender" class="form-label">Gender:</label>
                      <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input" type="radio"   id="inlineRadio1" name="gender" value="male" />
                        <label class="form-check-label" for="inlineRadio1">Male</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"  id="inlineRadio2" name="gender" value="female" />
                        <label class="form-check-label" for="inlineRadio2">Female</label>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="blood_group" class="form-label">Blood Group:</label>
                      <div class="input-group input-group-merge">
                        <select class="form-select" id="blood_group" name="blood_group" aria-label="Default select example">
                            <option selected>Please select</option>

                            @foreach (["O-ve", "O+ve", "A-ve", "A+ve", "B-ve", "B+ve", "AB-ve"] as $group)
                                <option value="{{ $group }}">{{ $group }}</option>";
                            @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="qualification" class="form-label">Qualification:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="qualification" name="qualification" placeholder="Enter qualification" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="esi_no" class="form-label">ESI No:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="esi_no" name="esi_no" placeholder="Enter ESI No" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="aadhaar" class="form-label">Aadhaar:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="aadhaar" name="aadhaar" placeholder="Enter Aadhaar No"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="pf_no" class="form-label">PF No:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="pf_no" name="pf_no" placeholder="Enter PF No"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="electoral_id" class="form-label">Electoral ID:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="electoral_id" name="electoral_id" placeholder="Enter Electoral ID"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="pan" class="form-label">PAN:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" id="pan" name="pan" placeholder="Enter PAN"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="date_of_birth" class="form-label">Date of Birth:</label>
                      <div class="input-group input-group-merge">
                      <input type="date" class="form-control" id="dob" name="dob" placeholder="Enter Date of Birth"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="group" class="form-label">Group:</label>
                      <div class="input-group input-group-merge">
                      <select class="form-select" id="group" name="group">
                        <option value="">Select Group</option>
                        <option value="G1">G1</option>
                        <option value="G2">G2</option>
                        <option value="G3">G3</option>
                        <option value="G4">G4</option>
                        <option value="G5">G5</option>
                      </select>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="group" class="form-label">Address:</label>
                      <div class="input-group input-group-merge">
                      <textarea type="text" class="form-control" id="address" name="address" placeholder="Select Address" rows="3">
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
                      <img id="imagePreview" src="" alt="" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;  border: 2px solid #ddd;"/>
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
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter mobile number"/>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="mobile_relationship" class="form-label">Relationship:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="mobile_relationship" id="mobile_relationship" placeholder="Enter relationship" />
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="landline" class="form-label">Landline:</label>
                      <div class="input-group input-group-merge">
                      <input type="text" class="form-control" name="landline" id="landline" placeholder="Enter landline"/>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="landline_relationship" class="form-label">Relationship:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="landline_relationship" id="landline_relationship" placeholder="Enter relationship"/>
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
                      <div class="input-group input-group-merge">
                      <select id="department_id" name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department ?? '' }}</option>

                        @endforeach
                      </select>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="designation" class="form-label">Designation:</label>
                      <div class="input-group input-group-merge">
                        <select id="designation_id" name="designation_id" class="form-select">
                          <option value="">Select Designation</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="smallInput" class="form-label">Join Date:</label>
                      <div class="input-group input-group-merge">
                        <input class="form-control" type="date" id="join_date" name="join_date"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="shift_id" class="form-label">Shift No:</label>
                      <div class="input-group input-group-merge">
                        <select class="form-select" id="shift_id" name="shift_id" aria-label="Default select">
                         @foreach ($work_shifts as $work_shift)
                            <option selected value="{{  $work_shift->id ?? '' }}">{{  $work_shift->shift_id ?? '' }}</option>
                         @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="login_limited_time" class="form-label">Login Limited Time:</label>
                        <div class="input-group input-group-merge">
                          <input type="time" class="form-control" id="login_limited_time" name="login_limited_time" placeholder="Enter login limited time">
                        </div>
                      </div>

                    <div class="col-md-4 mb-3">
                      <label for="role" class="form-label">Role:</label>
                      <div class="input-group input-group-merge">
                        <select class="form-select" id="role" name="role" aria-label="Default select">
                            <option selected value="">Please select</option>
                            @foreach ($roles as $role)
                                <option selected value="{{ $role->name }}">{{ $role->name ?? '' }}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="status" class="form-label">Status:</label>
                      <div class="input-group input-group-merge">
                        <select class="form-select" id="status" name="status" aria-label="Default select">
                          <option selected value="">Please select</option>
                           @foreach ($user_statuses as $user_status)
                                <option value="{{ $user_status->id }}">{{ $user_status->status_name ?? '' }}</option>
                           @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="leave_carry_info" class="form-label">Leave Carry Info:</label>
                      <div class="input-group input-group-merge">
                        <input id="leave_carry_info" name="leave_carry_info" class="form-control" type="text" placeholder="Enter leave carry info" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="appointment_status" class="form-label">Appointment Status:</label>
                      <div class="input-group input-group-merge">
                        <select class="form-select" id="appointment_status" name="appointment_status" aria-label="Default select">
                          <option selected value="">Please select</option>
                          <option value="probation">Probation</option>
                          <option value="confirmed">Confirmed</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="team_lead" class="form-label">Team Lead:</label>
                      <div class="input-group input-group-merge">
                        <select class="form-select" id="team_lead" name="team_lead" aria-label="Default select">
                          <option selected value="">Please select</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->user_id ?? '' }}">{{ $employee->full_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
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
                        <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter bank name"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="branch" class="form-label">Branch:</label>
                      <div class="input-group input-group-merge">
                          <input type="text" class="form-control" name="bank_branch" id="bank_branch" placeholder="Enter bank_branch" />
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="beneficiary_name" class="form-label">Beneficiary Name:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="beneficiary_name" id="beneficiary_name" placeholder="Enter beneficiary"/>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="account_number" class="form-label">Account Number:</label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter account number"/>
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
