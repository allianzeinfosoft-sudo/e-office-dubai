@extends('layouts.app')

@section('content')

<div class="container-fluid lock-bg">
    <div class="authentication-wrapper authentication-basic  container-p-y">
      <div class="authentication-inner py-4">
        <!-- Login -->
        <div class="theme-transparent">
          <div class="card-body theme-transparent">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
              <a href="feeds.html" class="app-brand-link">
                  <span class="logo-white">
                    <img class="w-100" src="{{ asset('assets/img/icons/ae-logo-light.png') }}">
                  </span>
                </a>
            </div>
            <!-- /Logo -->
            <div class="user-profile-img">
              <img class="w-50 mx-auto d-flex rounded-circle" src="../../assets/img/avatars/default-avatar.png" alt="5">
                 <h2>Change Your Password</h2>
            </div>


                 <form method="POST" class="mb-3" action="{{ route('password.change') }}">
                @csrf


                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                      <label class="form-label" for="password">New password </label>
                  </div>
                  <div class="input-group input-group-merge">
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required  placeholder="{{ __('en.password_placeholder')}}"  aria-describedby="password" autocomplete="current-password" tabindex="2" required>

                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="ti ti-eye-off"></i></span>
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                </div>
                </div>

                 <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                      <label class="form-label" for="password">Confirm password</label>
                  </div>
                  <div class="input-group input-group-merge">
                     <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required  placeholder="{{ __('en.password_placeholder')}}"  aria-describedby="confirmpassword" autocomplete="current-password" tabindex="2" required>

                    {{-- <span class="input-group-text cursor-pointer" id="togglePassword"></span>
                     --}}
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                </div>


                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
            </form>

            <p class="text-center">
              <span>New on our platform?</span>
              <a href="{{ route('login') }}" tabindex="6">
                <span>Sign In</span>
              </a>
            </p>


          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>


@endsection

@push('js')
   <script>
    //   show password

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const isPasswordVisible = passwordInput.type === 'text';
        passwordInput.type = isPasswordVisible ? 'password' : 'text';

        // Toggle icon class
        eyeIcon.classList.toggle('ti-eye');
        eyeIcon.classList.toggle('ti-eye-off');
    });
</script>
@endpush

