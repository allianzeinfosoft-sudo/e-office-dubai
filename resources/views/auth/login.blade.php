@extends('layouts.app')

@section('content')

<div class="authentication-wrapper authentication-cover authentication-bg" style="background-image: url('{{ asset('storage/'.$login_background_image) }}');">
    <div class="authentication-inner row">
      <!-- /Left Text -->
      <div class="d-none d-lg-flex col-lg-7 p-0">
            <div class="auth-cover-bg d-flex justify-content-center align-items-center">
            <img
                src="../assets/img/icons/logo-white.png"
                alt="auth-login-cover"
                class="img-fluid my-5 auth-illustration w-75"
                data-app-light-img="illustrations/auth-login-illustration-light.png"
                data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
            </div>
        </div>
      <!-- /Left Text -->

      <!-- Login -->
      <div class="d-flex col-12 col-lg-5 align-items-center bg-login p-sm-5 p-4">
        <div class="w-px-400 mx-auto">

              <div id="timedate" class="mb-5">
                <a id="h">12</a> :
                <a id="m">00</a> :
                <a id="s">00</a>
                <a id="mi"></a><br />
                <a id="mon" style="font-size: 20px;">January</a>
                <a id="d" style="font-size: 20px;">1</a>,
                <a id="y" style="font-size: 20px;">0</a>

              </div>
          <h3 class="mb-1 text-primary fw-bold">{{ __('en.welcome') }} </h3>
          <!-- <p class="mb-4">{{ __('en.slogan') }}</p>  -->
          <form id="formAuthentication" class="mb-3" method="POST" action="/login">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">{{ __('en.email')}}</label>
              <input id="email" type="email" class="form-control h-px-50 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('en.email_placeholder')}}" autofocus tabindex="1" >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">{{ __('en.password')}} </label>
                @if (Route::has('password.request'))

                    <a class="text-primary" href="mailto:hr@mail.allianzegroup.com?subject={{ 'Please Reset My Password' }}&body={{ urlencode('Reason:') }}">
                        <small>{{ __('en.forgot_password')}}</small>
                    </a>

                @endif
              </div>
              <div class="input-group input-group-merge">
                <input id="password" type="password" class="form-control h-px-50 @error('password') is-invalid @enderror" name="password" required  placeholder="{{ __('en.password_placeholder')}}"  aria-describedby="password" autocomplete="current-password" tabindex="2">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span class="input-group-text cursor-pointer" id="togglePassword"><i class="ti ti-eye-off" id="eyeIcon"></i></span>
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
            <button  type="submit" class="btn btn-primary d-grid w-100" tabindex="5">{{ __('en.login') }}</button>
          </form>

          <p class="text-center">
            <span>New on our platform?</span>
            <a class="text-primary" href="{{ route('register') }}" tabindex="6">
              <span>{{ __('en.register')}}</span>
            </a>
          </p>

        </div>
      </div>
      <!-- /Login -->
    </div>
  </div>

@endsection

@push('js')
<script>
  document.addEventListener("DOMContentLoaded", function () {
  initClock();
});

function updateClock() {
  var now = new Date();
  var sec = now.getSeconds(),
      min = now.getMinutes(),
      hou = now.getHours(),
      mo = now.getMonth(),
      dy = now.getDate(),
      yr = now.getFullYear();

  var months = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];

  // Add pad function to Number prototype if not already present
  Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < size) s = "0" + s;
    return s;
  };

  var tags = ["mon", "d", "y", "h", "m", "s"];
  var corr = [months[mo], dy, yr, hou.pad(2), min.pad(2), sec.pad(2)];

  for (var i = 0; i < tags.length; i++) {
    var el = document.getElementById(tags[i]);
    if (el) el.textContent = corr[i];
  }
}

function initClock() {
  updateClock();
  setInterval(updateClock, 1000); // Use function reference, not string
}


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
