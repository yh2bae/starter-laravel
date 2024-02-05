@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
    <x-breadcrumb :items="$breadcrumbItems" />
    <h4>List Users</h4>
    <p> This page is for managing users. </p>
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-2">Users</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-2 me-1 display-6">
                                    {{ $users->count() }}
                                </h4>
                            </div>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <div class="mdi mdi-account-outline mdi-24px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="d-flex justify-content-end pt-3 px-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                Add User
            </button>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table class="table" id="users-table">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('admin.users.partials.CreateModal')
    @include('admin.users.partials.EditModal')
    @include('admin.users.partials.EditPasswordModal')
@endsection


@push('js')
    <script>
        $(function() {
            try {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.index') }}",
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
                            data: 'email',
                            name: 'email'
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
                    order: [
                        [0, 'asc']
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

        // Event listener untuk tombol delete user
        $(document).on('click', '#deleteUserButton', function() {
            // Ambil ID user dari tombol yang ditekan
            var userId = $(this).data('id');
            console.log(userId);

            // Tampilkan konfirmasi penghapusan
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this user!',
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
                            url: `/users/destroy/${userId}`,
                            type: 'DELETE',
                            dataType: 'json',
                            data: {
                                _token: '{{ csrf_token() }}'
                            }
                        });

                        // Tampilkan pesan sukses dan perbarui tampilan jika penghapusan berhasil
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Perbarui data tabel pengguna
                            $('#users-table').DataTable().ajax.reload();
                        } else {
                            // Tampilkan pesan error jika terjadi kesalahan
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } catch (error) {
                        // Tangani kesalahan jika terjadi error saat mengirim permintaan
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
