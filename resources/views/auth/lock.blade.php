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
                    <img class="w-100" src="../../assets/img/icons/logo-white.png">
                  </span>
                </a>
            </div>
            <!-- /Logo -->
            <div class="user-profile-img">
              <img class="w-50 mx-auto d-flex rounded-circle" src="../../assets/img/avatars/default-avatar.png" alt="5">
            </div>

            <h4 class="mb-1 text-white text-center pt-2">User Name</h4>
            <p class="mb-4 text-center text-white">Enter your password to access Eoffice.</p>

              <form id="formAuthentication" class="mb-3" method="POST" action="/login">
                @csrf
                <div class="mb-3">
                  <input id="email" type="hidden" name="email" value="{{ $user->email }}" >
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                      <label class="form-label" for="password">{{ __('en.password')}} </label>
                    <a href="auth-forgot-password-basic.html">
                      @if (Route::has('password.request'))
                      <a href="mailto:hr@mail.allianzegroup.com?subject={{ 'Please Reset My Password' }}&body={{ urlencode('Reason:') }}">
                      <small>Forgot Password?</small>
                      </a>
                      @endif
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required  placeholder="{{ __('en.password_placeholder')}}"  aria-describedby="password" autocomplete="current-password" tabindex="2">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} tabindex="4">
                      <label class="form-check-label" for="remember">
                          {{ __('en.remember_me') }}
                      </label>
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

