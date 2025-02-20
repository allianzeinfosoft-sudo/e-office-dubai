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
            <form action="{{ route('users.store') }}" method="POST" name="userForm" id="userFormId" enctype="multipart/form-data">
              @csrf
            <div class="row"> 
              <!-- Form controls -->  
              <div class="col-md-12">
                <div class="card mb-4">
                  <h5 class="card-header">Personal Informations</h5>
                  <div class="row card-body">
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Employoee ID:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="employeeid"
                        id="exampleFormControlInput1"
                        placeholder="Enter username" readonly/>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Username:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="username"
                        placeholder="Enter username" />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Email ID:</label>
                      <input
                        class="form-control"
                        type="email"
                        id="exampleFormControlInput1"
                        name="email"
                        placeholder="Enter email"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Full Name:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="full_name"
                        placeholder="Enter fullname"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Reporting To:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="reporting_to"
                        placeholder="Enter reporting person"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Personal Email:</label>
                      <input
                        type="email"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="personal_email"
                        placeholder="Enter personal email"
                      />
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Gender:</label>
                      <div class="form-check form-check-inline mt-3">
                        <input
                          class="form-check-input"
                          type="radio"
                          name="inlineRadioOptions"
                          id="inlineRadio1"
                          name="gender"
                          value="male" />
                        <label class="form-check-label" for="inlineRadio1">Male</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input
                          class="form-check-input"
                          type="radio"
                          name="inlineRadioOptions"
                          id="inlineRadio2"
                          name="gender"
                          value="female" />
                        <label class="form-check-label" for="inlineRadio2">Female</label>
                      </div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Blood Group:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="blood_group" aria-label="Default select example">
                        <option selected>Please select</option>
                        <option value="O-ve">O-ve</option>
                        <option value="O+ve">O+ve</option>
                        <option value="A-ve">A-ve</option>
                        <option value="A+ve">A+ve</option>
                        <option value="B-ve">B-ve</option>
                        <option value="B+ve">B+ve</option>
                        <option value="AB-ve">AB-ve</option>
                        <option value="AB-ve">AB-ve</option>
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Qualification:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="qualification"
                        placeholder="Enter qualification"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">ESI No:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="esi_no"
                        placeholder="Enter ESI No"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Aadhaar:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="aadhaar"
                        placeholder="Enter Aadhaar No"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">PF No:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="pf_no"
                        placeholder="Enter PF No"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Electoral ID:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="electoral_id"
                        placeholder="Enter Electoral ID"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">PAN:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="pan"
                        placeholder="Enter PAN"
                      />
                    </div> 
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Date of Birth:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="date_of_birth"
                        placeholder="Enter Date of Birth"
                      />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Group:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="exampleFormControlInput1"
                        name="group"
                        placeholder="Select Group"
                      />
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
                      <img id="imagePreview" src="" alt="" class="rounded-circle" 
                           style="width: 150px; height: 150px; object-fit: cover; display: none; border: 2px solid #ddd;"/>
                  </div>
                    <div class="mb-3 mt-15"> 
                      <input class="form-control" type="file" id="formFile" name="profile_image" accept="image/*" onchange="previewImage(event)" />
                    </div> 
                  </div>
                </div>
              </div>


              <div class="col-xl-6">
                <div class="card mb-4">
                  <h5 class="card-header">Emergency Contact Information</h5>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Mobile No:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="mobile"
                        id="exampleFormControlInput1"
                        name="mobile_no"
                        placeholder="Enter mobile number"/>
                    </div>
                    <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Relationship:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="mobile_relationship"
                        id="exampleFormControlInput1"
                        placeholder="Enter relationship" />
                    </div>
                    <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Landline:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="landline"
                        id="exampleFormControlInput1"
                        placeholder="Enter landline"/>
                    </div>
                    <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Relationship:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="landline_relationship"
                        id="exampleFormControlInput1"
                        placeholder="Enter relationship"/>
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
                      <input
                        id="department"
                        name="department"
                        class="form-control"
                        type="text"
                        placeholder="Select department" />
                    </div> 
                    <div class="col-md-4 mb-3">
                      <label for="designation" class="form-label">Designation:</label>
                      <input id="designation"
                             name="designation" 
                             class="form-control" 
                             type="text" 
                             placeholder="Select designation" />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="smallInput" class="form-label">Join Date:</label>
                      <input class="form-control" type="date" id="join-date" name="join_date"/>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Shift No:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="shift_no" aria-label="Default select">
                        <option selected>Please select</option> 
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Role:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="role" aria-label="Default select">
                        <option selected>Please select</option> 
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Status:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="status" aria-label="Default select">
                        <option selected>Please select</option> 
                        <option value="new_user">New User</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="Resigned">Resigned</option>
                        <option value="admin">Admin</option>
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Login Limited Time:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="login_limited_time" aria-label="Default select">
                        <option selected>Please select</option> 
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Leave Carry Info:</label>
                      <input
                        id="leave_carry_info"
                        name="leave_carry_info"
                        class="form-control"
                        type="text"
                        placeholder="Enter leave carry info" />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Appointment Status:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="appointment_status" aria-label="Default select">
                        <option selected>Please select</option> 
                        <option value="probation">Probation</option>
                        <option value="confirmed">Confirmed</option>
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlSelect1" class="form-label">Team Lead:</label>
                      <select class="form-select" id="exampleFormControlSelect1" name="team_lead" aria-label="Default select">
                        <option selected>Please select</option> 
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
                      <label for="exampleFormControlInput1" class="form-label">Bank Name:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="bank_name"
                        id="exampleFormControlInput1"
                        placeholder="Enter bank name"/>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Branch:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="branch"
                        id="exampleFormControlInput1"
                        placeholder="Enter branch" />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Beneficiary Name:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="beneficiary_name"
                        id="exampleFormControlInput1"
                        placeholder="Enter beneficiary"/>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Account Name:</label>
                      <input
                        type="text"
                        class="form-control"
                        name="account_name"
                        id="exampleFormControlInput1"
                        placeholder="Enter account name"/>
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