@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
    <x-breadcrumb :items="$breadcrumbItems" />
    <h4>List Roles</h4>
    <p> This page is for managing roles. </p>
    <div class="card">
        <div class="d-flex justify-content-end pt-3 px-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRolesModal">
                Add Roles
            </button>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table class="table" id="roles-table">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Roles</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>  
    </div>

    @include('admin.roles.partials.CreateModal')
    {{-- @include('admin.permissions.partials.EditModal') --}}
@endsection

@push('js')
    <script>
        $(function() {
            try {
                $('#roles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('roles.index') }}",
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
    </script>
@endpush