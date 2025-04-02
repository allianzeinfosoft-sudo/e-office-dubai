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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}

            </div>
        @endif

        <div class="content-wrapper">
          <!-- Content -->


          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Apply Leave</h4>



          <div class="card mb-4">
            <form id="leaveForm" class="card-body" method="post" action="{{ route('leaves.store') }}">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                <div class="row g-3">

                <div class="col-md-6">
                  <label class="form-label" for="leave_from">Leave From</label>
                  <div class="input-group input-group-merge">
                    <input type="text" name="leave_from" value="{{ old('leave_from') }}" class="form-control" placeholder="YYYY-MM-DD" id="leave-from" />
                  </div>
                </div>


                <div class="col-md-12">
                    <label class="form-label" for="multicol-username">Leave Reason</label>
                        <div id="leave-editor">{{ strip_tags(old('reason')) }}                            </div>
                        <input type="text" name="reason" value="{{ strip_tags(old('reason')) }}" id="reason">
                </div>

                <div class="row mt-3">
                    <label class="form-label" for="multicol-username">Leave Type (Full/Half)</label>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input name="leave_type" class="form-check-input" type="radio" value="full_day"
                                   id="defaultRadio1" {{ old('leave_type') == 'full_day' ? 'checked' : '' }} />
                            <label class="form-check-label" for="defaultRadio1"> Full Day </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input name="leave_type" class="form-check-input" type="radio" value="half_day"
                                   id="defaultRadio2" {{ old('leave_type', 'half_day') == 'half_day' ? 'checked' : '' }} />
                            <label class="form-check-label" for="defaultRadio2"> Half Day </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <input name="leave_type" class="form-check-input" type="radio" value="off_day"
                                   id="defaultRadio3" {{ old('leave_type') == 'off_day' ? 'checked' : '' }} />
                            <label class="form-check-label" for="defaultRadio3"> Off Day </label>
                        </div>
                    </div>
                </div>


              </div>
              <div class="pt-4">
                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                <button type="reset" class="btn btn-label-secondary">Cancel</button>
              </div>
            </form>
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
