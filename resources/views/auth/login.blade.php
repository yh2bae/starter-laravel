@extends('layouts.guest')

@section('title', $pageTitle)
@section('content')
    <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
        <div class="w-px-400 mx-auto pt-5 pt-lg-0">
            <h4 class="mb-2">Welcome to Materialize! 👋</h4>
            <p class="mb-4">Please sign-in to your account and start the adventure</p>

            <div id="formAuthentication" class="mb-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-floating form-floating-outline mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                        autofocus />
                    <label for="email">Email</label>
                </div>
                <div class="mb-3">
                    <div class="form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" />
                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                    </div>
                    <a href="auth-forgot-password-cover.html" class="float-end mb-1">
                        <span>Forgot Password?</span>
                    </a>
                </div>
                <button class="btn btn-primary d-grid w-100" type="submit" id="sign_in_submit">
                    <span>Sign In</span>
                </button>
            </div>

            <p class="text-center mt-2">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                    <span>Create an account</span>
                </a>
            </p>

        </div>
    </div>
@endsection

@push('js')
    <script>
        const formAuthentication = document.querySelector('#formAuthentication');
        const validation = FormValidation.formValidation(
            formAuthentication, {
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter your email'
                            },
                            emailAddress: {
                                message: 'Please enter valid email address'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter your password'
                            },
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: '',
                        rowSelector: '.mb-3'
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),

                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                },
            }
        );

        $('#sign_in_submit').on('click', async function(e) {
            e.preventDefault();

            $(this).html(
                '<span>Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
            ).attr('disabled', true);

            // Validate the form
            const validationResult = await validation.validate();

            if (validationResult !== 'Valid') {
                $(this).html('<span>Sign In</span>').attr('disabled', false);
                return;
            }

            // Extract form data
            const email = $('#email').val();
            const password = $('#password').val();
            const _token = $('input[name="_token"]').val();

            const data = {
                email,
                password,
                _token
            };

            const response = await $.ajax({
                url: "{{ route('login.store') }}",
                type: 'POST',
                data,
                dataType: 'JSON',
                success: function(response) {
                    // Display a success message to the user
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function() {
                        // Redirect the user to the dashboard
                        window.location.href = "{{ route('dashboard') }}";
                    });
                },
                error: function(error) {
                    error = error.responseJSON;
                    // Display a specific error message to the user
                    Swal.fire({
                        text: error.message,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });

                    // Enable the submit button
                    $('#sign_in_submit').html('<span>Sign In</span>').attr('disabled', false);
                },
            });
            
            

        });



        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                $('#sign_in_submit').trigger('click')
            }
        })
    </script>
@endpush
