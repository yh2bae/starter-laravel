@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
    <x-breadcrumb :items="$breadcrumbItems" />
    <h4>List Permissions</h4>
    <p> This page is for managing permissions. </p>
    <div class="card">
        <div class="d-flex justify-content-end pt-3 px-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                Add Permission
            </button>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table class="table" id="permission-table">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Permission</th>
                        <th>Module Name</th>
                        <th>Assign To</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('admin.permissions.partials.CreateModal')
    @include('admin.permissions.partials.EditModal')
@endsection

@push('js')
    <script>
        $(function() {
            try {
                $('#permission-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('permissions.index') }}",
                    columns: [{
                            data: 'no',
                            name: 'no',
                            width: '5%',
                            searchable: false,
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'module_name',
                            name: 'module_name'
                        },
                        {
                            data: 'roles',
                            name: 'roles'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    lengthMenu: [
                        [5, 10,  25, 50, 100],
                        [5, 10, 25, 50, 100]
                    ],
                });
            } catch (error) {
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
            }
        });

        // // Event listener delete permission
        $(document).on('click', '#deletePermissionButton', function() {
            // Ambil ID user dari tombol yang ditekan
            var permissionId = $(this).data('id');
            // console.log(permissionId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this imaginary file!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-outline-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // Kirim permintaan DELETE ke server
                        const response = await $.ajax({
                            url: `/permissions/destroy/${permissionId}`,
                            type: 'DELETE',
                            dataType: 'json',
                            data: {
                                _token: '{{ csrf_token() }}'
                            }
                        });

                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $('#permission-table').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        });
    </script>
@endpush