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
                <h4 class="fw-bold py-3 mb-5"><span class="text-muted fw-light"> </span> Company Bank Configuration</h4>
                
                <div class="row justify-content-around px-3 px-lg-0">
                    <div class="col-xl-6 col-lg-7 mb-3 card card-bg1">
                    <div class="card-body">
                         <h5 class="card-action-title mb-4">Company Bank Configuration</h5>

                          <form action="{{ route('company-bank-configurations.store') }}" method="POST">
                              @csrf
                              
                              <div class="mb-3">
                                   <label class="form-label">Bank Name</label>
                                   <input type="text" name="bank_name" class="form-control"
                                        value="{{ old('bank_name', $config->bank_name ?? '') }}" placeholder="Enter Bank Name" required>
                              </div>

                              <div class="mb-3">
                                   <label class="form-label">Branch</label>
                                   <input type="text" name="branch" class="form-control"
                                        value="{{ old('branch', $config->branch ?? '') }}" placeholder="Enter Branch" required>
                              </div>

                              <div class="mb-3">
                                   <label class="form-label">IFSC Code</label>
                                   <input type="text" name="ifsc" class="form-control"
                                        value="{{ old('ifsc', $config->ifsc ?? '') }}" placeholder="Enter IFSC Code" required>
                              </div>

                              <div class="mb-3">
                                   <label class="form-label">Account Number</label>
                                   <input type="text" name="account_no" class="form-control"
                                        value="{{ old('account_no', $config->account_no ?? '') }}" placeholder="Enter Account Number" required>
                              </div>

                              <button type="submit" class="btn btn-primary">
                                   Save Configuration
                              </button>
                          </form>
                    </div>
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
