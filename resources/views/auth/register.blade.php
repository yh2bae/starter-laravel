@extends('layouts.guest')

@section('title', $pageTitle)
@section('content')
    <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
        <div class="w-px-400 mx-auto pt-5 pt-lg-0">
            <h4 class="mb-2">Adventure starts here 🚀</h4>
            <p class="mb-4">Make your app management easy and fun!</p>

            <div id="formAuthentication" class="mb-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your Name"
                        autofocus />
                    <label for="name">Name</label>
                </div>
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" id="email" name="email"
                        placeholder="Enter your email" />
                    <label for="email">Email</label>
                </div>
                <div class="mb-3 form-password-toggle">
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
                <div class="mb-3 form-password-toggle">
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input type="password" id="password_confirmation" class="form-control"
                                name="password_confirmation"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password_confirmation" />
                            <label for="password_confirmation">Confirm Password</label>
                        </div>
                        <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                        <label class="form-check-label" for="terms-conditions">
                            I agree to
                            <a href="javascript:void(0);">privacy policy & terms</a>
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary d-grid w-100" id="sign_up_submit">Sign up</button>
            </div>

            <p class="text-center mt-2">
                <span>Already have an account?</span>
                <a href="{{ route('login') }}">
                    <span>Sign in instead</span>
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
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter your name'
                            },
                            name: {
                                message: 'Please enter valid name'
                            }
                        }
                    },
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
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter your password confirmation'
                            },
                            identical: {
                                compare: function() {
                                    return formAuthentication.querySelector('[name="password"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
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

        $('#sign_up_submit').on('click', async function(e) {
            e.preventDefault();

            // Disable the button to prevent multiple clicks
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
            const name = $('#name').val();
            const email = $('#email').val();
            const password = $('#password').val();
            const _token = $('input[name="_token"]').val();

            const data = {
                name,
                email,
                password,
                _token
            };

            // Make the AJAX request
            const response = await $.ajax({
                url: "{{ route('register.store') }}",
                type: 'POST',
                data,
                dataType: 'JSON',
                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "{{ route('login') }}";
                    });
                },
                error: function(error) {
                    error = error.responseJSON;
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
                    $('#sign_up_submit').html('<span>Sign In</span>').attr('disabled', false);
                }
            });

        });

        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                $('#sign_up_submit').trigger('click')
            }
        })
    </script>
@endpush
