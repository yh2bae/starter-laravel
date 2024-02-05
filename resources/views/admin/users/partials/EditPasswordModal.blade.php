<div class="modal fade" id="editUserPasswordModal" tabindex="-1" aria-labelledby="editUserPasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body py-3 py-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">
                        Edit Password User
                    </h3>
                    <p class="pt-1">
                        Fill in the form below to edit password user
                    </p>
                </div>
                <div id="formEditUserPassword" novalidate class="fv-row">
                    <div class="row g-4">
                        <input type="hidden" name="uuid" id="uuidPassword">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-12">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="inputPassword" class="form-control" name="password"
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
                                        <input type="password" id="inputPasswordConfirmartion" class="form-control"
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
                            <button class="btn btn-primary me-sm-3 me-1" id="submitUpdatePasswordModal">Submit</button>
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
        $(document).on('click', '[data-bs-target="#editUserPasswordModal"]', function() {
            var userId = $(this).data('id');

            // Fetch user data via AJAX
            $.ajax({
                url: '/users/show/' + userId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var userData = response.data.user;
                    $('#uuidPassword').val(userData.uuid);

                    // Open the modal
                    $('#editUserPasswordModal').modal('show');
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        const formEditUserPassword = document.getElementById('formEditUserPassword');

        const vPassword = FormValidation.formValidation(
            formEditUserPassword, {
                fields: {
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required'
                            },
                            stringLength: {
                                min: 8,
                                message: 'Password must be at least 8 characters long'
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: 'Confirm Password is required'
                            },
                            identical: {
                                compare: function() {
                                    return formEditUserPassword.querySelector('[name="password"]')
                                        .value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    }
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

        $('#submitUpdatePasswordModal').on('click', function() {
            $(this).html(
                '<span>Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
            ).attr('disabled', true);

            const validationResult = vPassword.validate();

            if (validationResult !== 'Valid') {
                // Enable the button and show validation error message
                $(this).html('Submit').attr('disabled', false);
            }

            // Get the form data
            var formData = {
                uuid: $('#uuid').val(),
                password: $('#inputPassword').val(),
                password_confirmation: $('#inputPasswordConfirmartion').val(),
                _token: $('input[name=_token]').val(),
            };


            var responseData = $.ajax({
                url: '/users/update-password/' + formData.uuid,
                type: 'POST',
                data: formData,
                success: function(responseData) {
                    $('#editUserPasswordModal').modal('hide');
                    $('#submitUpdatePasswordModal').html('Submit').attr('disabled', false);
                    $('#formEditUserPassword').trigger('reset');
                    $('#users-table').DataTable().ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: responseData.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(xhr) {
                    $('#submitUpdatePasswordModal').html('Submit').attr('disabled', false);
                    $('#formEditUserPassword').trigger('reset');

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });

        });
    </script>
@endpush
