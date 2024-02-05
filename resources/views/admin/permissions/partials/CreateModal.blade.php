<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body py-3 py-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">
                        Add New Permission
                    </h3>
                    <p class="pt-1">
                        Fill in the form below to add new permission.
                    </p>
                </div>
                <div id="formCreatePermission" novalidate class="fv-row">
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
                                <input type="text" id="module_name" name="module_name" class="form-control"
                                    placeholder="Enter module name" />
                                <label for="module_name">Module Name</label>
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
        const formCreatePermission = document.getElementById('formCreatePermission');
        const validation = FormValidation.formValidation(
            formCreatePermission, {
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
                module_name: $('#module_name').val(),
                _token: $('input[name="_token"]').val()
            };

            const resetForm = () => {
                $('#name').val('');
                $('#module_name').val('');
            };

            const response = await $.ajax({
                url: "{{ route('permissions.store') }}",
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
                    $('#permission-table').DataTable().ajax.reload();
                    $('#createPermissionModal').modal('hide');
                    resetForm();
                    $('#submitModal').html('Submit').attr('disabled', false);
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    const firstItem = Object.keys(errors)[0];
                    const firstItemDOM = formCreatePermission.querySelector(
                        `[name="${firstItem}"]`
                    );
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
