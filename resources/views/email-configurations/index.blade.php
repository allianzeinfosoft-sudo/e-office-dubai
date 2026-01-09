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
                <h4 class="fw-bold py-3 mb-5"><span class="text-muted fw-light"> </span> Email Configuration</h4>
                <!-- Header -->
                
                <!--/ Header -->

                <!-- Email Configuration -->
                <div class="row justify-content-around px-3 px-lg-0">
                  <!-- Configuration Form -->
                  <!-- Email Configuration Form -->
                    <div class="col-xl-6 col-lg-7 mb-3 card card-bg1">
                    <div class="card-body">
                         <h5 class="card-action-title mb-4">Email Configuration</h5>

                          <form action="{{ isset($config) ? route('email-configurations.update', $config->id) : route('email-configurations.store') }}" method="POST">
                              @csrf
                              @if(isset($config))
                                   @method('PUT')
                              @endif

                              <!-- Protocol -->
                              <div class="mb-3">
                                   <label class="form-label">Mail Protocol</label>
                                   <select name="mail_protocol" class="form-control" required>
                                   <option value="imap" {{ old('mail_protocol', $config->mail_protocol ?? '')=='imap'?'selected':'' }}>IMAP</option>
                                   <option value="pop3" {{ old('mail_protocol', $config->mail_protocol ?? '')=='pop3'?'selected':'' }}>POP3</option>
                                   </select>
                              </div>

                              <!-- Host -->
                              <div class="mb-3">
                                   <label class="form-label">Incoming Mail Host</label>
                                   <input type="text" name="incoming_host" class="form-control"
                                        value="{{ old('incoming_host', $config->incoming_host ?? '') }}" placeholder="imap.yourdomain.com / pop.yourdomain.com" required>
                              </div>

                              <!-- Port -->
                              <div class="mb-3">
                                   <label class="form-label">Incoming Port</label>
                                   <input type="number" name="incoming_port" class="form-control"
                                        value="{{ old('incoming_port', $config->incoming_port ?? '') }}" placeholder="993 (IMAP) / 995 (POP3)" required>
                              </div>

                              <!-- Encryption -->
                              <div class="mb-3">
                                   <label class="form-label">Encryption</label>
                                   <select name="incoming_encryption" class="form-control" required>
                                   <option value="ssl" {{ old('incoming_encryption', $config->incoming_encryption ?? '')=='ssl'?'selected':'' }}>SSL</option>
                                   <option value="tls" {{ old('incoming_encryption', $config->incoming_encryption ?? '')=='tls'?'selected':'' }}>TLS</option>
                                   <option value="none" {{ old('incoming_encryption', $config->incoming_encryption ?? '')=='none'?'selected':'' }}>None</option>
                                   </select>
                              </div>

                              <!-- Username -->
                              <div class="mb-3">
                                   <label class="form-label">Incoming Username</label>
                                   <input type="text" name="incoming_username" class="form-control"
                                        value="{{ old('incoming_username', $config->incoming_username ?? '') }}" required>
                              </div>

                              <!-- Password -->
                              <div class="mb-3">
                                   <label class="form-label">Incoming Password</label>
                                   <input type="password" name="incoming_password" class="form-control"
                                        value="{{ old('incoming_password', $config->incoming_password ?? '') }}" required>
                              </div>

                              <button type="submit" class="btn btn-primary">
                                   {{ isset($config) ? 'Update Configuration' : 'Save Configuration' }}
                              </button>
                         </form>
                    </div>
                    </div>
                    <!--/ Email Configuration Form -->


                  
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

