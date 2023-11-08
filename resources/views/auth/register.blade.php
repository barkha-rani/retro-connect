@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">RetroConnect</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                    <p class="text-center small">Enter your details to create account</p>
                                </div>

                                <form class="row g-3" method="POST" action="{{ route('register') }}" id="signupForm">
                                    @csrf
                                    <div class="col-12">
                                        <label for="name" class="form-label">Your Name</label>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        <div class="invalid-feedback" id="nameError"></div>

                                        @error('name')
                                            <span class="invalid-feedback" id="nameError">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label">Your Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email">
                                        <span class="invalid-feedback" id="emailError"></span>

                                        @error('email')
                                            <span class="invalid-feedback" id="emailError">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-12">
                                        <label for="yourUsername" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                            <input type="text" name="username" class="form-control" id="yourUsername"
                                                required>
                                            <div class="invalid-feedback">Please choose a username.</div>
                                        </div>
                                    </div> --}}

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">
                                        <span class="invalid-feedback" id="passwordError"></span>

                                        @error('password')
                                            <span class="invalid-feedback" id="passwordError">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                                        <input id="confirmPassword" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password">
                                        <span class="invalid-feedback" id="confirmPasswordError"></span>
                                    </div>

                                    <div class="col-12">
                                        <label for="role" class="form-label">Role</label>
                                        <select id="role" class="form-select @error('role') is-invalid @enderror"
                                            name="role" required>
                                            <option value="">Select a role</option>
                                            <option value="teamMember" {{ request('team_id') ? 'selected' : '' }}>Team Member</option>
                                            <option value="scrumMaster">Scrum Master</option>
                                        </select>

                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12" id="team_id_container" style="{{ request('team_id') ? '' : 'display:none;' }}">
                                        <label for="team_id" class="form-label">Team Id</label>
                                        <input id="team_id" type="text"
                                            class="form-control @error('team_id') is-invalid @enderror" name="team_id"
                                            value="{{ request('team_id') }}" autocomplete="team_id">
                                        <span class="invalid-feedback" id="team_idError"></span>

                                        @error('team_id')
                                            <span class="invalid-feedback" id="team_idError">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="terms" type="checkbox" value=""
                                                id="acceptTerms" required>
                                            <label class="form-check-label" for="acceptTerms">I agree and accept the <a
                                                    href="#">terms and conditions</a></label>
                                            <div class="invalid-feedback">You must agree before submitting.</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Create Account</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Already have an account? <a
                                                href="{{ route('login') }}">Login</a></p>
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
            $("#role").change(function(event) {
                if ($("#role").val() === "teamMember") {
                    $("#team_id_container").show();
                } else {
                    $("#team_id_container").hide();
                }
            })

            $("#signupForm").submit(function(event) {
                let valid = true;
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let email = $("#email");
                let emailError = $("#emailError");
                let password = $("#password");
                let passwordPattern =
                    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                let passwordError = $("#passwordError");
                let confirmPassword = $("#confirmPassword");
                let confirmPasswordError = $("#confirmPasswordError");

                if (!email.val() || !emailRegex.test(email.val())) {
                    valid = false;
                    email.addClass("is-invalid");
                    emailError.text("Please enter a valid email address.");
                } else {
                    email.removeClass("is-invalid");
                    emailError.text("");
                }

                if (!password.val() || !passwordPattern.test(password.val())) {
                    valid = false;
                    password.addClass("is-invalid");
                    passwordError.text(
                        "Password must be at least 8 characters long, containing at least one capital letter, one small letter, one digit, and one special character."
                    );
                } else {
                    password.removeClass("is-invalid");
                    passwordError.text("");
                }

                if (password.val() !== confirmPassword.val()) {
                    valid = false;
                    confirmPassword.addClass("is-invalid");
                    confirmPasswordError.text("Passwords do not match.");
                } else {
                    confirmPassword.removeClass("is-invalid");
                    confirmPasswordError.text("");
                }

                if (!valid) {
                    event.preventDefault();
                }
                console.log("came here");
            });

            $("#signupName, #email, #password, #confirmPassword").on("input", function() {
                if ($(this).hasClass("is-invalid")) {
                    $(this).removeClass("is-invalid");
                    $("#" + this.id + "Error").text("");
                }
            });
        });
    </script>

@endsection
