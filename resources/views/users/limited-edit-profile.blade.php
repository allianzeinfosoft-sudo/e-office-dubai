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



          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="fw-bold py-3 mb-5"><span class="text-muted fw-light">User Profile /</span> Profile</h4>
                <!-- Header -->
                <div class="row justify-content-around">
                  <div class="col-12">
                    <div class="card mb-4 card-bg1">
                      <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                          @php
                            $image = ($user->employee?->profile_image) ? '/storage/' . $user->employee?->profile_image : '/assets/img/avatars/default-avatar.png';
                          @endphp
                          <img
                            src="{{ $image }}"
                            alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />

                            {{-- <img
                            src="../../assets/img/avatars/bg3.jpg"
                            alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" /> --}}
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                          <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                              <h4>{{ $user->employee->full_name ?? 'N/A'}}</h4>
                              <ul
                                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item"><i class="ti ti-color-swatch mt-n2"></i> {{ $user->employee->department->department ?? 'N/A' }}</li>
                                <li class="list-inline-item"><i class="ti ti-user mt-n2"></i> {{ $user->employee->designation->designation ?? 'N/A' }}</li>
                                <li class="list-inline-item"><a href="mailto:johnmathewallianze@mail.allianzegroup.com"><i class="ti ti-mail mt-n1"></i> {{ $user->email ?? 'N/A' }}</a></li>
                              </ul>
                            </div>
                            <span class="btn btn-primary">
                              <li class="list-inline-item"><i class="ti ti-user mt-n2"></i>{{ $user->employee->employeeID ?? 'N/A' }}</li>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Header -->

                <!-- User Profile Content -->
                <div class="row justify-content-around px-3 px-lg-0">
                  <!-- About User -->
                  <div class="col-xl-8 col-lg-8 mb-3 card card-bg1">
                    <div class="card-body">
                      <h5 class="card-action-title mb-0">Edit Profile (Employee can only edit limited details)</h5>
                      <form action="{{ route('users.limited_update', $user->id) }}" method="POST" name="userForm" id="userFormId" enctype="multipart/form-data">
                        @csrf

                            <div class="col-md-8 mb-3">
                                <label for="email_id" class="form-label">Personal Email ID:</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="email" id="personal_email" name="personal_email" value="{{ old('personal_email', $user->employee->personal_email ?? '')}}" placeholder="Enter personal email"  />
                                </div>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="email_id" class="form-label">Phone Number:</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control " type="number" id="phonenumber" name="phonenumber" value="{{ old('phonenumber', $user->employee->phonenumber ?? '')}}" placeholder="Enter phone number"  />
                                </div>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="email_id" class="form-label">Address:</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control" name="address" id="address" rows="5">{{ old('address', $user->employee->address ?? '')}}</textarea>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="col-md-4 mb-3">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit waves-effect waves-light">Update</button>
                                    <button type="reset" class="btn btn-label-secondary waves-effect" data-bs-dismiss="offcanvas">Cancel</button>
                                </div>
                            </div>

                      </form>
                    </div>
                  </div>
                </div>
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


@push('js')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/charts-apex.js') }}"></script>
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
@endpush

