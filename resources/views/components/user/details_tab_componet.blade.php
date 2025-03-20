<div class="bs-stepper wizard-modern wizard-numbered wizard-modern-example mt-2">
    <div class="bs-stepper-header">
      <div class="step" data-target="#account-details-modern">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">1</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Account Info</span>
            <span class="bs-stepper-subtitle">Account informations</span>
          </span>
        </button>
      </div>
      <div class="line">
        <i class="ti ti-chevron-right"></i>
      </div>
      <div class="step" data-target="#personal-info-modern">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">2</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Office Info</span>
            <span class="bs-stepper-subtitle">Office informations</span>
          </span>
        </button>
      </div>
      <div class="line">
        <i class="ti ti-chevron-right"></i>
      </div>
      <div class="step" data-target="#social-links-modern">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-circle">3</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Personal Info</span>
            <span class="bs-stepper-subtitle">Personal informations</span>
          </span>
        </button>
      </div>
    </div>



    <div class="bs-stepper-content">
      <form onSubmit="return false">
        <!-- Account Details -->
        <div id="account-details-modern" class="content">

          <div class="content-header mb-3">
            <h6 class="mb-0">Account Details</h6>
            <small>Account Details.</small>
          </div>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label" for="username-modern">Username</label>
              <input type="text" id="username-modern" class="form-control" placeholder="johndoe" />
            </div>
            <div class="col-sm-6">
              <label class="form-label" for="email-modern">Email</label>
              <input
                type="email"
                id="email-modern"
                class="form-control"
                placeholder="john.doe@email.com"
                aria-label="john.doe" />
            </div>
            <div class="col-sm-6 form-password-toggle">
              <label class="form-label" for="password-modern">Password</label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password-modern"
                  class="form-control"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password2-modern" />
                <span class="input-group-text cursor-pointer" id="password2-modern"
                  ><i class="ti ti-eye-off"></i
                ></span>
              </div>
            </div>
            <div class="col-sm-6 form-password-toggle">
              <label class="form-label" for="confirm-password-modern">Confirm Password</label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="confirm-password-modern"
                  class="form-control"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="confirm-password-modern2" />
                <span class="input-group-text cursor-pointer" id="confirm-password-modern2"
                  ><i class="ti ti-eye-off"></i
                ></span>
              </div>
            </div>
            <div class="col-12 d-flex justify-content-between">
              <button class="btn btn-label-secondary btn-prev" disabled>
                <i class="ti ti-arrow-left me-sm-1 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">Previous</span>
              </button>
              <button class="btn btn-primary btn-next">
                <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                <i class="ti ti-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>


        <!-- Office Info -->
        <div id="personal-info-modern" class="content p-4 shadow-sm bg-white rounded">
            <div class="content-header mb-4 text-center">
              <h5 class="fw-bold text-primary">Office Information</h5>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Department</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value=" {{ $user->employee->department->department ?? 'N/A' }}" readonly />
                    </div>
                </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Designation</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-person-badge"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value=" {{ $user->employee->designation->designation ?? 'N/A' }}" readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Join Date</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-calendar-check"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                    value="{{ $user->employee->join_date ? \Carbon\Carbon::parse($user->employee->join_date)->format('d-m-Y') : 'N/A' }}"
                    readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Shift No</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-clock"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->workshift->shift_id ?? 'N/A' }}" readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">User Status</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-person-check"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->userStatus->status_name ?? 'N/A' }}" readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Login Limited Time</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-clock-history"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->login_limited_time ?? 'N/A' }}" readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Leave Carry Info</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-calendar-check"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="20" readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Appointment Status</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-calendar-check"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->appointment_status ?? 'N/A' }}" readonly />
                    </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="first-name-modern">Team Lead</label>
                <div class="input-group">
                <span class="input-group-text bg-light-1"><i class="bi bi-people"></i></span>
                <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                        value="{{ $user->employee->team_lead ?? 'N/A' }}" readonly />
                </div>
              </div>


              <div class="col-12 d-flex justify-content-between mt-3">
                <button class="btn btn-outline-primary shadow-sm btn-prev">
                  <i class="ti ti-arrow-left me-2"></i> Previous
                </button>
                <button class="btn btn-primary shadow-sm btn-next">
                  Next <i class="ti ti-arrow-right ms-2"></i>
                </button>
              </div>
            </div>
          </div>

        <!-- Social Links -->
        <div id="social-links-modern" class="content p-4 shadow-sm bg-white rounded">
            <div class="content-header mb-4 text-center">
                <h5 class="fw-bold text-primary">Office Information</h5>
            </div>
            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Employee ID</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-person-badge"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->employeeID ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Personal Email</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-envelope"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->personal_email ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Gender</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-gender-ambiguous"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->gender ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Blood Group</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-droplet"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->blood_group ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Qualification</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-mortarboard"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->qualification ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">ESI No: </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-shield-check"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->esi_no ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Aadhaar</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-fingerprint"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->aadhaar ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">PF No</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-bank"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->pf_no ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Electoral ID</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->electoral_id ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">PAN</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->pan ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Date of Birth</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-calendar"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->dob ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Group</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-people"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->group ?? 'N/A' }}" readonly />
                    </div>
                </div>


                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->address ?? 'N/A' }}" readonly />
                    </div>
                </div>
                <hr>
                <div class="content-header mb-4 text-center">
                    <h5 class="fw-bold text-primary">Emergency Contact Information</h5>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->mobile_number ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Mobile Contact Person</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->mobile_relationship ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Landline</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->landline ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Landline Contact person</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->landline_relationship ?? 'N/A' }}" readonly />
                    </div>
                </div>
                <hr>
                <div class="content-header mb-4 text-center">
                    <h5 class="fw-bold text-primary">Bank Information</h5>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Bank Name</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->bank_name ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Branch</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->bank_branch ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Beneficiary Name</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->beneficiary_name ?? 'N/A' }}" readonly />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="first-name-modern">Account Number</label>
                    <div class="input-group">
                    <span class="input-group-text bg-light-1"><i class="bi bi-building"></i></span>
                    <input type="text" id="first-name-modern" class="form-control shadow-sm text-center bg-light-1 border-0"
                           value="{{ $user->employee->account_number ?? 'N/A' }}" readonly />
                    </div>
                </div>




            <div class="col-12 d-flex justify-content-between">
              <button class="btn btn-label-secondary btn-prev">
                <i class="ti ti-arrow-left me-sm-1 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">Previous</span>
              </button>
              {{-- <button class="btn btn-success btn-submit">Submit</button> --}}
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
