@extends('layouts.auth')

@section('title', 'Login')

@section('content')
  <div class="container">
    <section class="section register d-flex flex-column align-items-center justify-content-center py-4" style="height:calc(100vh - 120px)">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex justify-content-center py-4">
              <a href="{{ route("dashboard") }}" class="logo d-flex align-items-center w-auto">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">RetroConnect</span>
              </a>
            </div><!-- End Logo -->
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your email & password to login</p>
                </div>
                <form class="row g-3" method="POST" action="{{ route('login') }}"
                  id="loginForm">
                  @csrf
                  <div class="col-12">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" name="email"
                      class="form-control @error('email') is-invalid @enderror" id="email"
                      required value="{{ old("email") }}">
                    <div class="invalid-feedback" id="emailError"></div>
                    @error('email')
                      <span class="invalid-feedback" id="emailError">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password"
                      class="form-control @error('password') is-invalid @enderror" id="password"
                      required>
                    <div class="invalid-feedback" id="passwordError"></div>
                    @error('password')
                      <span class="invalid-feedback" id="passwordError">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember"
                      {{ old('remember') ? 'checked' : '' }}>
                      <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Don't have account? <a href="{{ route('register') }}">Create an
                        account</a></p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@section('script')
  <script>
    $(document).ready(function() {
      $("#loginForm").submit(function(event) {
        let valid = true;
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let email = $("#email");
        let emailError = $("#emailError");
        let password = $("#password");
        let passwordError = $("#passwordError");

        if (!email.val() || !emailRegex.test(email.val())) {
          valid = false;
          email.addClass("is-invalid");
          emailError.text("Please enter a valid email address.");
        } else {
          email.removeClass("is-invalid");
          emailError.text("");
        }

        if (!password.val()) {
          valid = false;
          password.addClass("is-invalid");
          passwordError.text("Please enter your password.");
        } else {
          password.removeClass("is-invalid");
          passwordError.text("");
        }

        if (!valid) {
          event.preventDefault();
        }
      });

      $("#email, #password").on("input", function() {
        if ($(this).hasClass("is-invalid")) {
          $(this).removeClass("is-invalid");
          $("#" + this.id + "Error").text("");
        }
      });
    });
  </script>
@endsection
