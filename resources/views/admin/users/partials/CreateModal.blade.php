<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body py-3 py-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">
                        Add New User
                    </h3>
                    <p class="pt-1">
                        Fill in the form below to add a new user.
                    </p>
                </div>
                <div id="formCreateUser" novalidate class="fv-row">
                    <div class="row g-4">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter your name" />
                                <label for="name">Name</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter your email" />
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="roles" aria-label="roles" name="roles">
                                    <option selected disabled value="">Select Role</option>
                                    @forelse ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @empty
                                        <option value="" disabled>No roles available</option>
                                    @endforelse
                                </select>
                                <label for="roles">Roles</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                        <label for="password">Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password_confirmation" class="form-control"
                                            name="password_confirmation"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password_confirmation" />
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-primary me-sm-3 me-1" id="submitModal">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        const formCreateUser = document.getElementById('formCreateUser');
        const roles = jQuery(formCreateUser.querySelector('[name="roles"]'));
        const validation = FormValidation.formValidation(
            formCreateUser, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Name is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 50,
                                message: 'Name must be more than 3 and less than 50 characters long'
                            },

                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email is required'
                            },
                            emailAddress: {
                                message: 'The value is not a valid email address'
                            }
                        }
                    },
                    roles: {
                        validators: {
                            notEmpty: {
                                message: 'Roles is required'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required'
                            },
                            stringLength: {
                                min: 8,
                                message: 'Password must be more than 8 characters long'
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: 'Password confirmation is required'
                            },
                            identical: {
                                compare: function() {
                                    return formCreateUser.querySelector('[name="password"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        $('#submitModal').on('click', async function(e) {
            e.preventDefault();

            $(this).html(
                '<span>Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
            ).attr('disabled', true);


            // Validate the form
            const validationResult = await validation.validate();

            if (validationResult !== 'Valid') {
                // Enable the button and show validation error message
                $(this).html('Submit').attr('disabled', false);
            }

            const formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                roles: $('#roles').val(),
                password: $('#password').val(),
                _token: $('input[name="_token"]').val()
            };
            const resetForm = () => {
                $('#name').val('');
                $('#email').val('');
                $('#roles').val('');
                $('#password').val('');
                $('#password_confirmation').val('');
            };

            const response = await $.ajax({
                url: "{{ route('users.store') }}",
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                   Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#users-table').DataTable().ajax.reload();
                    $('#createUserModal').modal('hide');
                    resetForm();
                    $('#submitModal').html('Submit').attr('disabled', false);
                }, 
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    const firstItem = Object.keys(errors)[0];
                    const firstItemDOM = formCreateUser.querySelector('[name="' + firstItem + '"]');
                    const firstErrorMessage = errors[firstItem][0];

                    // Remove existing error classes
                    const formGroup = firstItemDOM.closest('.fv-row');
                    const formControl = firstItemDOM.closest('.form-floating');
                    formGroup.classList.remove('has-danger');
                    formControl.classList.remove('is-invalid');

                    // Add the new error classes
                    formGroup.classList.add('has-danger');
                    formControl.classList.add('is-invalid');

                    // Set the new error message
                    Swal.fire({
                        icon: 'error',
                        title: 'error!',
                        text: firstErrorMessage,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('#submitModal').html('Submit').attr('disabled', false);
                }
            });
        });
    </script>
@endpush
