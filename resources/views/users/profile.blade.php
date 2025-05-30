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
                              <li class="list-inline-item"><i class="ti ti-user mt-n2"></i>AIS-{{ $user->employee->employeeID ?? 'N/A' }}</li>

                            </span>
                            @if ($user->employee->status === 4)
                                <span class="btn btn-primary">
                                    <li class="list-inline-item"><i class="ti-xs ti ti-lock me-1"></i>Resigned On {{ optional($user->employee)->resigned_date ? \Carbon\Carbon::parse($user->employee->resigned_date)->format('d-m-Y') : '' }}</li>
                                </span>
                            @endif


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
                  <div class="col-xl-5 col-lg-5 mb-3 card card-bg1">
                    <div class="card-body">
                      <h5 class="card-action-title mb-0">About</h5>
                      <ul class="list-unstyled mt-3 mb-0">
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-eye mt-n2"></i>Age: &nbsp;</span><span>{{ $user->employee->age ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-calendar mt-n2"></i>Date of Birth: &nbsp;</span><span>{{ $user->employee->dob ? \Carbon\Carbon::parse($user->employee->dob)->format('d-m-Y') : 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-user mt-n2"></i>Gender: &nbsp;</span><span>{{ isset($user->employee->gender) ? ucfirst(\Illuminate\Support\Str::camel($user->employee->gender)) : 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-heart mt-n2"></i>Blood Group: &nbsp;</span><span>{{ $user->employee->blood_group ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold "><i class="ti ti-phone-call mt-n2"></i>Contact: &nbsp;</span>
                          <span>{{ $user->employee->phonenumber ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-calendar mt-n2"></i>Join Date: &nbsp;</span><span>{{ $user->employee->dob ? \Carbon\Carbon::parse($user->employee->join_date)->format('d-m-Y') : 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-user mt-n2"></i>Reporting To: &nbsp;</span><span>{{ $user->employee->reportingToEmployee ? $user->employee->reportingToEmployee->full_name : 'N/A'  }}</span>
                        </li>
                        <hr>
                        <li class="d-flex align-items-center mb-3">
                          <div class="timeline-event">
                            <div class="timeline-header">
                              <i class="ti ti-medal mt-n2"></i><span class="fw-bold">Qualification:</span>
                            </div>
                            <div class="mx-1 mt-2">
                              <span>{{ $user->employee->qualification ?? 'N/A' }}</span>
                            </div>
                          </div>
                        </li>

                        <li class="d-flex align-items-center mb-3">
                          <div class="timeline-event">
                            <div class="timeline-header">
                              <i class="ti ti-calendar mt-n2"></i><span class="fw-bold">Experiencee:</span>
                            </div>
                            <div class="mx-1 mt-2">
                              <span class="btn btn-label-success">{{ $experiance ?? 'N/A' }} </span>
                            </div>
                          </div>
                        </li>

                        <li class="d-flex align-items-center mb-0">
                          <div class="timeline-event">
                            <div class="timeline-header">
                              <i class="ti ti-map mt-n2"></i><span class="fw-bold">Address:</span>
                            </div>
                            <div class="mx-1 mt-2">
                              <span >{{ $user->employee->address ?? 'N/A' }}</span>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <!--/ About User -->

                  <!-- Other Info -->
                  <div class="col-lg-6 col-xl-6 mb-3 card card-bg1">
                    <div class="card-body">
                      <h5 class="card-action-title mb-0">Other Info</h5>
                      <ul class="list-unstyled mt-3 mb-0">
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-mail mt-n1"></i>Email: &nbsp;</span>
                          <span>{{ $user->employee->personal_email ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-brand-tabler mt-n1"></i>ESI No: &nbsp;</span>
                          <span>{{ $user->employee->esi_no ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-layout-cards mt-n1"></i>PF No: &nbsp;</span>
                          <span>{{ $user->employee->pf_no ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-credit-card mt-n1"></i>PAN: &nbsp;</span>
                          <span>{{ $user->employee->pan ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-brand-mastercard mt-n1"></i>Aadhaar (UID): &nbsp;</span>
                          <span>{{ $user->employee->aadhaar ?? 'N/A' }}</span>
                        </li>
                        {{-- <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-cardboards mt-n1"></i>Electroal ID: &nbsp;</span>
                          <span>{{ $user->employee->electoral_id ?? 'N/A' }}</span>
                        </li> --}}
                        <li class="mb-3">
                          <div class="d-flex justify-content-between align-items-start">
                            <div class="">
                              <h6 class="mb-0"><i class="ti ti-phone-call mt-n1"></i><span class="fw-bold ">Emergency Contact:</span></h6>
                            </div>
                            <div>
                              <p class="mb-0 "><span class="text-muted">{{ $user->employee->mobile_relationship ?? 'N/A' }}: </span>{{ $user->employee->mobile_number ?? 'N/A' }} </p>
                              <p class="mb-0 mt-2"><span class="text-muted">{{ $user->employee->landline_relationship ?? 'N/A' }} (LandLine): </span>{{ $user->employee->landline ?? 'N/A' }} </p>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <hr>
                      <h5 class="card-action-title mb-0">Banking Information </h5>
                      <ul class="list-unstyled mb-0 mt-3">
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-building mt-n2"></i>Bank Name: &nbsp;</span>
                          <span>{{ $user->employee->bank_name ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-hexagons mt-n2"></i>Branch: &nbsp;</span>
                          <span>{{ $user->employee->bank_branch ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-user mt-n2"></i>Beneficiary Name: &nbsp;</span>
                          <span>{{ $user->employee->beneficiary_name ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-id-badge mt-n2"></i>Account Number: &nbsp;</span>
                          <span>{{ $user->employee->account_number ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between mb-3">
                          <span class="fw-bold"><i class="ti ti-mail mt-n1"></i>IFSC Code: &nbsp;</span>
                          <span>{{ $user->employee->ifsc ?? 'N/A' }}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <!--/ Other Info -->
                </div>

                <div class="row justify-content-around px-3 px-lg-0">
                  <!-- Activity Timeline -->
                  <div class="col-xl-7 col-lg-7 mb-3 card card-bg1">
                    <div class="card-header align-items-center">
                      <h5 class="card-action-title mb-0">Leave Summary</h5>
                    </div>
                    <div class="card-body row pb-0">
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">{{ $leave_info->this_month_leave ?? 0  }}</h5>
                              <small>This Month Leave(s)</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-bolt ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">{{ $leave_info->approved_leaves ?? 0 }}</h5>
                              <small>Total Leave(s) Taken</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-drone ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">{{ $leave_info->balance_leave ?? 0 }}</h5>
                              <small>Leave Balance</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-drone ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">{{ $leave_info->pending_leaves ?? 0 }}</h5>
                              <small>Pending Leave(s)</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-server ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">{{ $leave_info->leave_alloted ?? 0 }}</h5>
                              <small>Total Leave(s) Alloted</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-hourglass-high ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-5">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-1">{{ $leave_info->past_year_leavecount ?? 0 }}</h5>
                              <small>Past Year Leave(s)</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-calendar ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12 col-sm-12 mb-5">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <div>
                                <span class="badge bg-label-warning me-2">Full: {{ $leave_info->full_day_leavecount ?? 0 }}</span>
                                <span class="badge bg-label-primary me-2">Half: {{ $leave_info->half_day_leavecount ?? 0 }}</span>
                                <span class="badge bg-label-success">Off Days: {{ $leave_info->off_day_leavecount ?? 0 }}</span>
                              </div>
                              <small>Leave(s) Category Wise</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-success rounded-pill p-2">
                                <i class="ti ti-info-circle"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!--/ Activity Timeline -->

                  <!-- Donut Chart -->
                  <div class="col-xl-4 col-lg-4 card card-bg1 mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <div>
                        <h5 class="card-title mb-0">Current Attendance Analytics</h5>
                      </div>

                    </div>
                    <div class="card-body ">
                      <x-charts.attendance-donut-chart
                          id="dashboaddAttendanceDonut"
                          :labels="['Completed', 'Half Days', 'Off', 'Custom', 'Holidays', 'Leaves']"
                          :donutsData="[
                              $attendance_analytics['completed_days'] ?? 0,
                              $attendance_analytics['incomplete_or_half_days'] ?? 0,
                              $attendance_analytics['off_days'] ?? 0,
                              $attendance_analytics['custom_days'] ?? 0,
                              $attendance_analytics['total_holidays'] ?? 0,
                              $attendance_analytics['total_leaves'] ?? 0
                          ]"
                          :backgroundColors="['#fee802', '#3fd0bd', '#826bf8', '#2b9bf4', '#f86624', '#ea5455']"
                          height="360px"
                      />
                    </div>
                  </div>
                  <!-- /Donut Chart -->
                </div>

                <!-- Projects table -->
                <!-- <div class="row justify-content-around px-3 ">
                  <div class="card">
                      <div class="card-datatable table-responsive">
                        <table class="datatables-projects table border-top">
                          <thead>
                            <tr>
                              <th>Project Name	</th>
                              <th>Task Name	</th>
                              <th>Performance</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                  </div>
                </div> -->
                <!--/ Projects table -->

                <!--/ User Profile Content -->
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

