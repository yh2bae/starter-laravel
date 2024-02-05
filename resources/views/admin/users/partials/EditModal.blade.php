<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body py-3 py-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">
                        Edit User
                    </h3>
                    <p class="pt-1">
                        Fill in the form below to edit user
                    </p>
                </div>
                <div id="formEditUser" novalidate class="fv-row">
                    <div class="row g-4">
                        <input type="hidden" name="uuid" id="uuid">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="dataName" name="name" class="form-control"
                                    placeholder="Enter your name" />
                                <label for="name">Name</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="email" id="dataEmail" name="email" class="form-control"
                                    placeholder="Enter your email" disabled />
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="dataRoles" aria-label="roles" name="roles">
                                    <option selected disabled>Select Role</option>
                                    @forelse ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @empty
                                        <option disabled value="">No roles available</option>
                                    @endforelse
                                </select>
                                <label for="roles">Roles</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-primary me-sm-3 me-1" id="submitUpdateModal">Submit</button>
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
        $(document).on('click', '[data-bs-target="#editUserModal"]', function() {
            var userId = $(this).data('id');

            // Fetch user data via AJAX
            $.ajax({
                url: '/users/show/' + userId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var userData = response.data.user;

                        // Populate the modal fields with user data
                        $('#uuid').val(userData.uuid);
                        $('#dataName').val(userData.name);
                        $('#dataEmail').val(userData.email);

                        // Set the selected option for roles
                        $('#dataRoles option').each(function() {
                            if ($(this).val() === userData.role) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });

                        // Open the modal
                        $('#editUserModal').modal('show');
                    } else {
                        // Handle error response
                        console.error(response.message);
                    }
                },
                error: function(error) {
                    // Handle AJAX error
                    console.error(error);
                }
            });
        });

        const formEditUser = document.getElementById('formEditUser');
        const roleValidation = jQuery(formEditUser.querySelector('[name="roles"]'));

        const v = FormValidation.formValidation(
            formEditUser, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Name is required'
                            }
                        }
                    },
                    roles: {
                        validators: {
                            notEmpty: {
                                message: 'Role is required'
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

        $('#submitUpdateModal').on('click', async function(e) {
            e.preventDefault();

            $(this).html(
                '<span>Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
            ).attr('disabled', true);


            const validationResult = await v.validate();

            if (validationResult !== 'Valid') {
                // Enable the button and show validation error message
                $(this).html('Submit').attr('disabled', false);
            }

            // Get the form data
            var formData = {
                uuid: $('#uuid').val(),
                name: $('#dataName').val(),
                roles: $('#dataRoles').val(),
                _token: $('input[name=_token]').val(),
            };

            // Send the PUT request
            var response = await $.ajax({
                url: '/users/update/' + formData.uuid,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    // Handle success response
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#users-table').DataTable().ajax.reload();
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
                    // console.log(firstErrorMessage);

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

            // Enable the button and show validation error message
            $(this).html('Submit').attr('disabled', false);
        });
    </script>
@endpush
