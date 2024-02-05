<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body py-3 py-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">
                        Edit Permission
                    </h3>
                    <p class="pt-1">
                        Fill in the form below to edit permission.
                    </p>
                </div>
                <div id="formEditPermission" novalidate class="fv-row">
                    <div class="row g-4">
                        <input type="hidden" name="id" id="id">
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
                                <input type="text" id="dataModule_name" name="module_name" class="form-control"
                                    placeholder="Enter module name" />
                                <label for="module_name">Module Name</label>
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
        $(document).on('click', '[data-bs-target="#editPermissionModal"]', function() {
            var permissionId = $(this).data('id');
            console.log(permissionId);

            // Fetch permission data via AJAX
            $.ajax({
                url: '/permissions/show/' + permissionId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var permissionData = response.data.permission;

                        // Populate the modal fields with permission data
                        $('#id').val(permissionData.id);
                        $('#dataName').val(permissionData.name);
                        $('#dataModule_name').val(permissionData.module_name);


                        // Open the modal
                        $('#editPermissionModal').modal('show');
                    } else {
                        // Handle error response
                        Swal.fire({
                            icon: 'error',
                            title: 'error!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'error!',
                        text: error.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        const formEditPermission = document.getElementById('formEditPermission');

        const v = FormValidation.formValidation(
            formEditPermission, {
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
                    module_name: {
                        validators: {
                            notEmpty: {
                                message: 'Module Name is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 50,
                                message: 'Module Name must be more than 3 and less than 50 characters long'
                            },
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
        $('#submitUpdateModal').on('click', async function(e) {
            e.preventDefault();

            // Disable the button and show loading message
            const $submitButton = $(this);
            $submitButton.html(
                '<span>Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
            ).attr('disabled', true);

            try {
                // Validate the form
                await v.validate();

                // Get the form data
                var formData = {
                    id: $('#id').val(),
                    name: $('#dataName').val(),
                    module_name: $('#dataModule_name').val(),
                    _token: $('input[name=_token]').val(),
                };

                // Send the PUT request
                var response = await $.ajax({
                    url: '/permissions/update/' + formData.id,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Handle success response
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Close the modal
                        $('#editUserModal').modal('hide');

                        // Reload the DataTable
                        $('#permission-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        const errors = xhr.responseJSON.errors;
                        const firstItem = Object.keys(errors)[0];
                        const firstItemDOM = $(`[name="${firstItem}"]`);
                        const firstErrorMessage = errors[firstItem][0];

                        // Remove existing error classes
                        firstItemDOM.closest('.fv-row').removeClass('has-danger');
                        firstItemDOM.closest('.form-floating').removeClass('is-invalid');

                        // Add the new error classes
                        firstItemDOM.closest('.fv-row').addClass('has-danger');
                        firstItemDOM.closest('.form-floating').addClass('is-invalid');

                        // Show the error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: firstErrorMessage,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    complete: function() {
                        // Enable the button after request completion
                        $submitButton.html('Submit').attr('disabled', false);
                    }
                });
            } catch (error) {
                // Handle validation errors
                // Enable the button and show validation error message
                $submitButton.html('Submit').attr('disabled', false);
                error = error.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.data.error,
                    showConfirmButton: false,
                    timer: 1500
                });

                
            }
        });
    </script>
@endpush
